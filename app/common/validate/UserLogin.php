<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\common\validate;

use think\Validate;

class UserLogin extends Validate
{

    protected $rule = [
        'account' => 'require|length:5,20|regex:^(?!_)(?![0-9])(?!.*?_$)[a-zA-Z0-9_]+$',
        'password' => 'require|length:5,20',
        'verify_code' => 'require|length:4,8',
    ];

    protected $message = [
        'account.require' => '账号不能为空',
        'account.length' => '请正确输入您的账号',
        'account.regex' => '请正确输入您的账号',
        'password.require' => '密码不能为空',
        'password.length' => '请正确输入您的密码',
        'verify_code.require' => '请输入验证码',
        'verify_code.length' => '请正确输入验证码',
    ];

}
