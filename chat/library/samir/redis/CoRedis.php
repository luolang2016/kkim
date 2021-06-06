<?php


namespace chat\library\samir\redis;


use Swoole\Coroutine;

class CoRedis
{
    private static $instance;
    private static $box = [];

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 获取redis
     * @param $key : 库标志
     * @param string $channel | client:客户端pool:连接池
     * @return Coroutine\Redis
     */
    public function init($key, $channel = 'client'): Coroutine\Redis
    {
        $cid = Coroutine::getCid();
        if (!isset(self::$box[$cid][$key])) {
            self::$box[$cid][$key] = ($channel == 'pool') ? RedisPool::getInstance()->get($key) : RedisClient::getInstance()->get($key);
        }
        defer(function () use ($cid) {
            if (isset(self::$box[$cid])) {
                unset(self::$box[$cid]);
            }
        });
        return self::$box[$cid][$key];
    }

}