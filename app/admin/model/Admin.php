<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\admin\model;

use app\common\utils\Encrypt;
use app\common\utils\Tools;
use think\Model;

class Admin extends Model
{

    public function login($params)
    {
        $data = self::where('account', $params['username'])->find();
        if ($data) {
            $enc = new Encrypt();
            $md5Pwd = $enc->optimizedSaltPwd($params['password'], $data->salt);
            if ($md5Pwd != $data->password) {
                return '密码不正确.';
            }
            if ($data->status != 'normal') {
                return '账号已禁用.';
            }
            $data->last_login_time = time();
            $data->last_login_ip = Tools::getClientIp();
            $data->login_count = $data->login_count + 1;
            $data->save();
            return true;
        }
        return '用户名或密码有误.';
    }

}
