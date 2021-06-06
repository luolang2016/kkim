<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace chat\service;

class Loader
{

    public static function load()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace("\\", "/", trim($class, "\\"));
            $file_name = BASE_PATH . DS . $file . '.php';
            if (file_exists($file_name)) {
                include_once($file_name);
            }
        });
    }

}
