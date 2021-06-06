<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\chat\model;

use think\Model;

class ChatRecord extends Model
{

    public function getRecords($params, $limit = 200)
    {
        $where = [
            ['rec_type', '=', '0'],
            ['sender_id', '=', intval($params['meid'])],
            ['receive_id', '=', intval($params['toid'])]
        ];
        //$field = '';
        return self::where($where)->order('id', 'DESC')->limit($limit)->select();
    }

}
