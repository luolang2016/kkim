<?php


namespace chat\library\samir\mysql;


use Swoole\Coroutine\MySQL;

class MysqlManager
{
    private static $instance;
    public static $mode;
    public static $dbConf = [];

    private function __construct()
    {
    }

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 注册Mysql策略
     * @param string $mode 集群cluster;读写分离proxy
     * @return $this
     */
    public function regMode(string $mode)
    {
        if (!in_array($mode, ['cluster', 'proxy'])) {
            throw new \RuntimeException('mysql mode error');
        } else {
            self::$mode = $mode;
        }
        return $this;
    }

    /**
     * 注册配置
     * @param $key | 库标志
     * @param array $config | 配置
     * @return $this
     */
    public function reg($key, array $config)
    {
        //注册mysql连接配置
        if (!isset(self::$dbConf[$key])) {
            if (isset($config['host'], $config['port'], $config['user'], $config['password'], $config['database'], $config['charset'])) {
                self::$dbConf[$key] = $config;
            } else {
                throw new \RuntimeException('mysql db config error');
            }
        } else {
            throw new \RuntimeException('请勿重复注册');
        }
        return $this;
    }

    /**
     * 初始化连接池，仅当配置中有pool配置才会被初始化
     */
    public function initPool()
    {
        $keyArr = array_keys(self::$dbConf);
        foreach ($keyArr as $key) {
            if (isset(self::$dbConf[$key]['pool']['min'], self::$dbConf[$key]['pool']['max'], self::$dbConf[$key]['pool']['retry'])) {
                MysqlPool::getInstance()->init($key);
            }
        }
    }

    /**
     * 连接协程mysql
     * @param $key
     * @return MySQL
     */
    public function connect($key)
    {
        $mysql = new MySQL();
        $mysql->connect(self::$dbConf[$key]);
        return $mysql;
    }

}