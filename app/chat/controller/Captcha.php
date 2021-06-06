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
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use think\facade\Session;

class Captcha extends BaseController
{

    public function index()
    {
        //不想用大写（它默认是含有大写），所以声明这个用于指定字符串
        $p = new PhraseBuilder(5, 'abcdefghijklmnpqrstuvwxyz1234567890');
        $b = new CaptchaBuilder(null, $p);
        $b->build();
        $code = $b->getPhrase();
        Session::set('k_chat_verify_code', $code);
        Session::save();
        header('Content-type: image/jpeg');
        $b->output();
    }

}
