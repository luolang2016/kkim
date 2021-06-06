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
use think\App;

class Base extends BaseController
{

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

}
