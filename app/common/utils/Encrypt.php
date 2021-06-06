<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\common\utils;

class Encrypt
{

    /**
     * 使用密码和盐值对密码进行MD5加密
     */
    public static function optimizedSaltPwd($password, $salt, $saltGain = 1)
    {
        //过滤参数
        if (!is_numeric($saltGain) || (intval($saltGain) < 0 || intval($saltGain) > 35)) {
            exit();
        }
        //对Md5盐值进行变换
        $tempSaltMd5 = md5($salt);
        for ($i = 0; $i < strlen($tempSaltMd5); $i++) {
            if (ord($tempSaltMd5[$i]) < 91 && ord($tempSaltMd5[$i]) > 32) {
                $tempSaltMd5[$i] = chr(ord($tempSaltMd5[$i]) + $saltGain);
            }
        }
        //计算哈希值
        $tempPwdMd5 = md5($password);
        return strtoupper(md5($tempSaltMd5 . $tempPwdMd5 . strrev($tempSaltMd5)));
    }

}
