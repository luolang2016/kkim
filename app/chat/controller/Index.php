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

use app\chat\model\Users;
use think\facade\View;

class Index extends Base
{

    public function index()
    {
        View::assign('f_style_ver', $this->_setting['f_style_ver']);
        return View::fetch();
    }

}
