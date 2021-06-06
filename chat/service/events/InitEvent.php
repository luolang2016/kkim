<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace chat\service\events;

use chat\library\samir\mysql\Db;
use chat\library\samir\mysql\MysqlManager;
use chat\library\samir\redis\RedisManager;
use chat\service\WebSocket;

class InitEvent extends WebSocket
{

    //服务启动时触发
    public function onStart(\Swoole\Websocket\Server $server)
    {
        $t = date('Y-m-d H:i:s');
        echo "[{$t}]服务已启动，正在运行中..." . PHP_EOL;
    }

    //注：worker_num设置为多少，本方法就会执行多少次
    public function onWorkerStart(\Swoole\Websocket\Server $server, $workerId)
    {
        //mysql连接池
        $this->mysqlPoolInit();
        //redis连接池
        $this->redisPoolInit();

        $t = date('Y-m-d H:i:s');
        echo "[{$t}]进程({$workerId})启动..." . PHP_EOL;
    }

    #region Mysql和Redis连接池初始化

    //初始化mysql连接池
    private function mysqlPoolInit()
    {
        MysqlManager::getInstance()->regMode('cluster')->reg('c1', $this->_config['database'])->initPool();
        //TODO::如果对象已关闭？
        $this->_db_pool = Db::getInstance()->init('pool');
        MessageEvent::instance()->_db_pool = $this->_db_pool;
    }

    //初始化redis连接池
    private function redisPoolInit()
    {
        RedisManager::getInstance()->reg('master', $this->_config['redis'])->initPool();
        //$redis = CoRedis::getInstance()->init('master', 'pool');
    }

    #endregion

}
