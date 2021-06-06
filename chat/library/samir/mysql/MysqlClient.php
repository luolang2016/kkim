<?php


namespace chat\library\samir\mysql;


use Swoole\Coroutine\MySQL;

class MysqlClient
{
    private static $instance;

    static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key)
    {
        $retry = 0;
        back:
        $retry++;
        $mysql = MysqlManager::getInstance()->connect($key);
        if ($mysql->connected && $mysql->errno == 0) {
            return $mysql;
        } elseif ($retry <= 3) {
            goto back;
        } else {
            throw new \RuntimeException('mysql client connect error');
        }
    }
}