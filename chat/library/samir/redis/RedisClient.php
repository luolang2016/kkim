<?php
// +---------------------------------
// | Copyright (c) 2016-2019 52cp.cn
// +---------------------------------
// | DateTime: 2019-08-21 00:46
// +---------------------------------
// | Author: samir <84570829@qq.com>
// +---------------------------------


namespace chat\library\samir\redis;


use Swoole\Coroutine\Redis;

class RedisClient
{
    private static $instance;

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 获取客户端
     * @param $key
     * @return Redis
     */
    public function get($key)
    {
        $retry = 0;
        back:
        $retry++;
        $redis = RedisManager::getInstance()->connect($key);
        if ($redis->connected) {
            defer(function () use ($redis) {
                $redis->close();
            });
            return $redis;
        } elseif ($retry <= 3) {
            goto back;
        } else {
            throw new \RuntimeException('redis client get error');
        }
    }


}