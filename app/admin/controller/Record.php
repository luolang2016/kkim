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

use think\facade\View;

class Record extends Base
{

    public function list()
    {
        $pager = $this->pagerParams();
        $m = new \app\admin\model\ChatRecord();
        $list = $m->getList($this->_admin, $this->_all_get, $pager);
        View::assign('dataList', $list);
        return View::fetch();
    }

}
