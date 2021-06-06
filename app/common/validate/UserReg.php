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

class UserReg extends Validate
{

    protected $rule = [
        'account' => 'require|length:5,20|regex:^(?!_)(?![0-9])(?!.*?_$)[a-zA-Z0-9_]+$',
        'password' => 'require|length:5,20',
        'cfm_pwd' => 'require|length:5,20|confirm:password',
        'verify_code' => 'require|length:4,8',
    ];

    protected $message = [
        'account.require' => '账号不能为空',
        'account.length' => '账号长度为5到20个字符',
        'account.regex' => '账号只能是字母或与数字的组合',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度必须为5到20个字符',
        'cfm_pwd.require' => '确认密码不能为空',
        'cfm_pwd.length' => '两次密码输入不一致',
        'cfm_pwd.confirm' => '两次密码输入不一致',
        'verify_code.require' => '请输入验证码',
        'verify_code.length' => '请正确输入验证码',
    ];

}
