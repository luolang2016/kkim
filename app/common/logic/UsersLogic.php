<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\common\logic;

use app\common\utils\Encrypt;
use app\common\utils\Tools;
use app\common\validate\UserLogin;
use app\common\validate\UserReg;
use app\common\model\Users;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Session;

class UsersLogic
{

    protected $_user_id = 0;
    protected $_userInfo;

    public function loginUser($params, $timestamp)
    {
        //数据验证
        $v = new UserLogin();
        $result = $v->check($params);
        if (!$result) {
            return $v->getError();
        }
        //验证码
        $v_code = Session::get('k_chat_verify_code', '');
        if ($params['verify_code'] != $v_code) {
            return '验证码输入错误';
        }

        //读取用户和登录
        return $this->login($params, $timestamp);
    }

    public function regUser($params, $timestamp)
    {
        //数据验证
        $v = new UserReg();
        $result = $v->check($params);
        if (!$result) {
            return $v->getError();
        }
        //验证码
        $v_code = Session::get('k_chat_verify_code', '');
        if ($params['verify_code'] != $v_code) {
            return '验证码输入错误';
        }

        //创建用户
        $result = (new Users())->createNewUser($params, $timestamp);
        if ($result['res'] === false) {
            return $result['msg'];
        }
        $this->setUserId($result['data']['id']);
        $this->setUserInfo($result['data']);

        //登录用户
        $this->loginSet();

        return true;
    }

    private function login($params, $timestamp)
    {
        $usInfo = (new Users())->getUser($params, $timestamp);
        if ($usInfo) {
            $this->setUserId($usInfo['id']);
            $this->setUserInfo($usInfo);

            //登录用户
            $this->loginSet();

            return true;
        }
        return '账号或密码有误';
    }

    public function loginSet()
    {
        //一些服务器保存用户登录状态的记录
    }

    private function setUserId($userId)
    {
        $this->_user_id = $userId;
    }

    public function getUserId()
    {
        return $this->_user_id;
    }

    private function setUserInfo($userInfo)
    {
        $this->_userInfo = $userInfo;
    }

    public function getUserInfo()
    {
        return $this->_userInfo;
    }

}
