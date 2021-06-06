<?php
// +---------------------------------
// | Copyright (c) 2016-2019 52cp.cn
// +---------------------------------
// | DateTime: 2019/6/18 13:23
// +---------------------------------
// | Author: samir <84570829@qq.com>
// +---------------------------------

namespace chat\library\samir\mysql;


use Swoole\Coroutine\Channel;
use Swoole\Coroutine\MySQL;

class MysqlPool
{
    private static $instance;
    private static $pool = [];

    private function __construct()
    {
    }

    private function __clone()
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
     * 初始化
     * @param $key
     */
    public function init($key)
    {
        self::$pool[$key] = new Channel(MysqlManager::$dbConf[$key]['pool']['max']);
        while (!self::$pool[$key]->isEmpty()) {
            self::$pool[$key]->pop();
        }
        if (MysqlManager::$dbConf[$key]['pool']['min'] > 0) {
            for ($i = 1; $i <= MysqlManager::$dbConf[$key]['pool']['min']; $i++) {
                go(function () use ($key) {
                    $mysql = MysqlManager::getInstance()->connect($key);
                    if ($mysql->connected && $mysql->errno == 0) {
                        $this->put($key, $mysql);
                    }
                });
            }
        }
    }

    /**
     * 取池
     * @param $key
     * @return MySQL
     */
    public function get($key)
    {
        if (self::$pool[$key]->isEmpty()) {
            $retry = 0;
            back:
            $retry++;
            $mysql = MysqlManager::getInstance()->connect($key);
            if ($mysql->connected && $mysql->errno == 0) {
                return $mysql;
            } elseif ($retry <= MysqlManager::$dbConf[$key]['pool']['retry']) {
                goto back;
            } else {
                throw new \RuntimeException('Mysql connection failed');
            }
        } else {
            $mysql = self::$pool[$key]->pop();
            return $mysql;
        }
    }

    /**
     * 回收连接
     * @param $key | 池标志
     * @param $mysql | mysql连接
     */
    public function put($key, $mysql)
    {
        if (self::$pool[$key]->isFull()) {
            self::$pool[$key]->pop();
        }
        self::$pool[$key]->push($mysql);
    }


}
