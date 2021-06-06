<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

declare (strict_types=1);

namespace app\chat\controller;

use app\chat\model\ChatRecord;
use think\facade\Db;

class History extends Base
{

    public function index()
    {
        //$record = (new ChatRecord())->getRecords($this->_all_get);
        $sql = "SELECT a.id,sender_id,receive_id,content,create_time,s.id AS sender_us_id,"
            . "s.avatar AS sender_avatar,s.nickname AS sender_nickname,r.id AS receive_us_id,"
            . "r.avatar AS receive_avatar,r.nickname AS receive_nickname "
            . "FROM (SELECT id,sender_id,receive_id,content,create_time FROM kkim_chat_record "
            . "WHERE rec_type=0 AND ((sender_id=" . intval($this->_all_get['meid']) . " AND receive_id="
            . intval($this->_all_get['toid']) . ") OR (sender_id=" . intval($this->_all_get['toid'])
            . " AND receive_id=" . intval($this->_all_get['meid']) . ")) ORDER BY id DESC LIMIT 200) AS a "
            . "LEFT JOIN kkim_users AS s ON a.sender_id=s.id "
            . "LEFT JOIN kkim_users r ON a.receive_id=r.id ORDER BY a.id ASC";
        $record = Db::query($sql);
        $this->success('success', $record);
    }

}
