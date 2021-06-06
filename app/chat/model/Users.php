<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\chat\model;

use think\facade\Db;
use think\Model;

class Users extends Model
{

    public function getAllUsers($uid = '0', $limit = 30)
    {
        $where = [
            ['status', '=', 'normal'],
            ['id', '<>', $uid]
        ];
        //$field = '';
        return self::where($where)->order('id', 'DESC')->limit($limit)->select();
    }

}
