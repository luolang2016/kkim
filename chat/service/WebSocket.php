<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace chat\service;

use chat\service\events\InitEvent;
use chat\service\events\MessageEvent;

include_once('Loader.php');

class WebSocket
{

    public $_config; //配置
    public $_svr; //Server对象
    public $_db_pool; //从连接池获取的数据库连接对象
    public $_redis_pool; //从连接池获取的REDIS连接对象
    public static $_instance; //本实例
    protected static $_ins = []; //子类的实例

    public function init($ip = '0.0.0.0', $port = 9502)
    {
        //autoload
        Loader::load();

        //数据库及REDIS配置
        $this->setConfig();

        //创建Server对象，监听指定的IP及端口
        $this->_svr = new \Swoole\Websocket\Server($ip, $port);
        $this->_svr->set([
            'worker_num' => 1,
            //'daemonize' => true,
            //'log_file' => __DIR__ . '/log/server.log',
            //'ssl_cert_file' => '/your_path/file.crt',
            //'ssl_key_file' => '/your_path/file.key',
        ]);

        $this->setVariable();

        $this->_svr->on('Start', [InitEvent::instance(), 'onStart']);
        $this->_svr->on('WorkerStart', [InitEvent::instance(), 'onWorkerStart']);
        $this->_svr->on('Open', [MessageEvent::instance(), 'onOpen']);
        $this->_svr->on('Message', [MessageEvent::instance(), 'onMessage']);
        $this->_svr->on('Close', [MessageEvent::instance(), 'onClose']);
    }

    public function start()
    {
        $this->_svr->start();
    }

    public function setVariable()
    {
        InitEvent::instance()->_config = $this->_config;
        InitEvent::instance()->_svr = $this->_svr;
        MessageEvent::instance()->_config = $this->_config;
        MessageEvent::instance()->_svr = $this->_svr;
    }

    public static function instance()
    {
        $className = get_called_class();
        if (!isset(static::$_ins[$className]) || !static::$_ins[$className]) static::$_ins[$className] = new static();
        return static::$_ins[$className];
    }

    #region mysql及redis配置

    //mysql及redis配置
    private function setConfig()
    {
        $this->_config = require_once dirname(__DIR__) . '/config/config.php';
    }

    #endregion

    #region 实例

    //实例化
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    #endregion

}
