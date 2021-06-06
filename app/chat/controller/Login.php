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

use app\BaseController;
use app\common\logic\UsersLogic;
use think\facade\View;

class Login extends BaseController
{

    public function index()
    {
        View::assign('f_style_ver', $this->_setting['f_style_ver']);
        return View::fetch();
    }

    public function post()
    {
        $logic = new UsersLogic();
        $result = $logic->loginUser($this->_all_post, $this->_cur_timestamp);
        if ($result === true) {
            $usInfo = $logic->getUserInfo();
            unset($usInfo['password']);
            $this->success('登录成功', ['us_info' => $usInfo, 'url' => '/chat/index']);
        } else {
            $this->error($result);
        }
    }

}
