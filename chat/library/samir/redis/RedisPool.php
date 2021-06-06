<?php
// +---------------------------------
// | Copyright (c) 2016-2019 52cp.cn
// +---------------------------------
// | DateTime: 2019/7/26 10:22
// +---------------------------------
// | Author: samir <84570829@qq.com>
// +---------------------------------


namespace chat\library\samir\redis;


use Swoole\Coroutine\Channel;
use Swoole\Coroutine\Redis;

class RedisPool
{
    private static $instance;
    private static $pool = [];
    private static $lastTime = [];

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 建立池管道
     * @param $key ,池标记
     */
    public function initPool($key)
    {
        self::$pool[$key] = new Channel(RedisManager::$dbConf[$key]['pool']['max']);
        $this->init($key);
        $this->keepalive($key);
    }

    /**
     * 初始化池连接
     * @param $key
     */
    private function init($key)
    {
        while (!self::$pool[$key]->isEmpty()) {
            self::$pool[$key]->pop();
        }
        if (RedisManager::$dbConf[$key]['pool']['min'] > 0) {
            for ($i = 1; $i <= RedisManager::$dbConf[$key]['pool']['min']; $i++) {
                go(function () use ($key) {
                    $redis = RedisManager::getInstance()->connect($key);
                    if ($redis->connected) {
                        $this->put($key, $redis);
                    }
                });
            }
        }
        self::$lastTime[$key] = time();
    }

    /**
     * 连接保活
     * @param $key
     */
    private function keepalive($key)
    {
        swoole_timer_tick(RedisManager::$dbConf[$key]['pool']['checkInterval'] * 1000, function () use ($key) {
            if ((time() - self::$lastTime[$key]) > RedisManager::$dbConf[$key]['pool']['keepalive']) {
                $this->init($key);
            };
        });
    }

    /**
     * 获取redis客户端
     * @param $key :库标记
     * @return Redis
     */
    public function get($key)
    {
        $retry = 0;
        back:
        $retry++;
        if (self::$pool[$key]->length() > 0) {
            $redis = self::$pool[$key]->pop();
            if ($redis->ping() === 0) {
                self::$lastTime[$key] = time();
                defer(function () use ($key, $redis) {
                    $this->put($key, $redis);
                });
                return $redis;
            } elseif ($retry <= RedisManager::$dbConf[$key]['pool']['retry']) {
                goto back;
            } else {
                throw new \RuntimeException('redis pool error');
            }
        } else {
            $redis = RedisManager::getInstance()->connect($key);
            if ($redis->connected) {
                self::$lastTime[$key] = time();
                defer(function () use ($key, $redis) {
                    $this->put($key, $redis);
                });
                return $redis;
            } elseif ($retry <= RedisManager::$dbConf[$key]['pool']['retry']) {
                goto back;
            } else {
                throw new \RuntimeException('redis client error');
            }
        }
    }

    /**
     * 回收连接
     * @param $key
     * @param $redis
     */
    public function put($key, $redis)
    {
        if (self::$pool[$key]->isFull()) {
            self::$pool[$key]->pop();
        }
        self::$pool[$key]->push($redis);
    }

}
