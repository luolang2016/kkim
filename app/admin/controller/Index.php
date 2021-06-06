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

use think\facade\Session;
use think\facade\View;

class Index extends Base
{

    public function index()
    {
        return View::fetch();
    }

    public function welcome()
    {
        return View::fetch();
    }

    public function logout()
    {
        Session::clear();
        Session::save();
        return redirect('/admin/login');
    }

}
