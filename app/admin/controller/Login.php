<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\Admin;
use app\BaseController;
use app\common\utils\Validator;
use think\facade\Session;
use think\facade\View;

class Login extends BaseController
{

    public function index()
    {
        return View::fetch();
    }

    public function post()
    {
        (!$this->request->isPost() || !$this->request->isAjax()) && $this->error('请求非法.');
        $this->login();
    }

    private function login()
    {
        $this->checkParams();
        $flag = (new Admin())->login($this->_all_post);
        $flag !== true && $this->error($flag);
        Session::set('kkim_mgr_sess', $this->_all_post['username']);
        Session::save();
        $this->success('登录成功.', ['url' => '/admin/index']);
    }

    private function checkParams()
    {
        if (!isset($this->_all_post['username']) || !Validator::isUserName($this->_all_post['username'], 5, 18)) {
            $this->error('登录账号格式不正确,长度5到25位.');
        }
        if (!isset($this->_all_post['password']) || empty($this->_all_post['password'])) {
            $this->error('登录密码不能为空.');
        }
        $len = strlen($this->_all_post['password']);
        if ($len < 5 || $len > 18) {
            $this->error('登录密码长度必须为5到18位.');
        }
    }

}
