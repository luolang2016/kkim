<?php


namespace chat\library\samir\redis;


use Swoole\Coroutine\Redis;

class RedisManager
{
    private static $instance;
    public static $dbConf = [];

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 注册
     * @param $key : 库标记
     * @param $config : 连接配置
     * @return $this
     */
    public function reg($key, array $config)
    {
        if (!isset(self::$dbConf[$key])) {
            if (isset($config['host'], $config['port'])) {
                self::$dbConf[$key] = $config;
            } else {
                throw new \RuntimeException('redis config error');
            }
        } else {
            throw new \RuntimeException("redis #{$key} has reg");
        }
        return $this;
    }

    /**
     * 初始化连接池，仅当配置中有正确的pool配置才会被初始化
     */
    public function initPool()
    {
        $keyArr = array_keys(self::$dbConf);
        foreach ($keyArr as $key) {
            if (isset(self::$dbConf[$key]['pool']['min'], self::$dbConf[$key]['pool']['max'], self::$dbConf[$key]['pool']['retry'], self::$dbConf[$key]['pool']['checkInterval'], self::$dbConf[$key]['pool']['keepalive'])) {
                RedisPool::getInstance()->initPool($key);
            }
        }
    }

    /**
     * 获取连接
     * @param $key
     * @return Redis
     */
    public function connect($key)
    {
        $redis = new Redis();
        $redis->setOptions([
            'connect_timeout' => 1,
            'timeout' => -1,
            'serialize' => false,
            'reconnect' => 1,
            'compatibility_mode' => true
        ]);
        $redis->connect(self::$dbConf[$key]['host'], self::$dbConf[$key]['port']);
        if (isset(self::$dbConf[$key]['auth']) && self::$dbConf[$key]['auth'] != '') {
            $redis->auth(self::$dbConf[$key]['auth']);
        }
        return $redis;
    }

}