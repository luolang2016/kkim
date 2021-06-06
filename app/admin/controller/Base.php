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
use think\App;
use think\facade\Session;
use think\facade\View;

class Base extends BaseController
{

    public $_admin;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->checkMgrLogin();
    }

    private function checkMgrLogin()
    {
        $username = Session::get('kkim_mgr_sess', '');
        if (empty($username)) {
            Session::clear();
            header('location:/admin/login', true);
            exit();
        }
        $this->_admin = (new Admin())->where('account', $username)->find();
        if (empty($this->_admin)) {
            Session::clear();
            header('location:/admin/login', true);
            exit();
        }
        if ($this->_admin->status != 'normal') {
            Session::clear();
            header('location:/admin/login', true);
            exit();
        }
        View::assign('admin_username', $this->_admin->account);
    }

}
