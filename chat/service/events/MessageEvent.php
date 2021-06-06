<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace chat\service\events;

use chat\library\samir\redis\CoRedis;
use chat\service\WebSocket;

class MessageEvent extends WebSocket
{

    public function onOpen(\Swoole\Websocket\Server $server, \Swoole\Http\Request $req)
    {
        $server->push($req->fd, $this->makeData('5', '您已成功连接服务器'));
    }

    public function onMessage(\Swoole\Websocket\Server $server, \Swoole\WebSocket\Frame $frame)
    {
        $data = json_decode($frame->data, true);
        if ($data) {
            switch ($data['type']) {
                case '1':
                    $this->bindUsIdFd($server, $frame, $data['from']);
                    break;
                case '2':
                case '3':
                    $this->sendMsgToUser($server, $frame, $data);
                    break;
                case '4':
                    $this->userExit($server, $frame, $data['from']);
                    break;
                case '0':
                default:
                    $server->push($frame->fd, $this->makeData('5', '发送的数据类型不正确'));
                    break;
            }
        } else {
            $server->push($frame->fd, $this->makeData('5', '发送的数据不合法'));
        }
    }

    public function onClose(\Swoole\Websocket\Server $server, $fd)
    {
        $t = date('Y-m-d H:i:s');
        echo "[{$t}]客户端【{$fd}】关闭连接" . PHP_EOL;
    }

    private function sendMsgToUser(\Swoole\Websocket\Server $server, \Swoole\WebSocket\Frame $frame, $data)
    {
        //TODO::检测用户ID是否正确格式。
        $redis = CoRedis::getInstance()->init('master', 'pool');
        $fd = $redis->get('KKIM:UID:FD:' . $data['to']);
        if ($fd !== null && $fd !== false) {
            //记录数据到数据库
            $content = str_replace("'", '', $data['message']);
            $arr = [
                'sender_id' => $data['from'],
                'receive_id' => $data['to'],
                'content' => $content,
                'create_time' => time()
            ];
            $insertId = $this->_db_pool->table('kkim_chat_record')->insert($arr);
            $server->push($fd, $this->makeData('2', $data['message'], time(), $data['from']));
        }
    }

    private function bindUsIdFd(\Swoole\Websocket\Server $server, \Swoole\WebSocket\Frame $frame, $userId)
    {
        //TODO::检测用户ID是否正确格式。
        $redis = CoRedis::getInstance()->init('master', 'pool');
        $redis->set('KKIM:UID:FD:' . $userId, $frame->fd);
    }

    private function userExit(\Swoole\Websocket\Server $server, \Swoole\WebSocket\Frame $frame, $userId)
    {
        //TODO::检测用户ID是否正确格式。
        $redis = CoRedis::getInstance()->init('master', 'pool');
        $redis->del('KKIM:UID:FD:' . $userId);
    }

    private function makeData($type, $message, $time = 0, $from = '')
    {
        $data = [
            'type' => $type,
            'message' => $message,
            'from' => $from,
            'time' => $time
        ];
        return json_encode($data);
    }

}
