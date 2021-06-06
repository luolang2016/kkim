<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

include_once('service/WebSocket.php');

define('DS', DIRECTORY_SEPARATOR); //分隔符
define('BASE_PATH', dirname(dirname(__FILE__)) . DS);

$ws = chat\service\WebSocket::getInstance();
$ws->init();
$ws->start();
