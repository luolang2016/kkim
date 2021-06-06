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

class User extends Base
{

    public function friend()
    {
        //得到全部注册用户（测试版直接允许发送消息，不需要加好友）
        //TODO::后期加入群聊后，也要列出群
        $uid = isset($this->_all_get['uid']) ? intval($this->_all_get['uid']) : '0';
        $users = (new Users())->getAllUsers($uid, 50);
        $this->success('success', $users);
    }

}
