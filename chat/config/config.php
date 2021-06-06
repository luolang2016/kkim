<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

return [
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'd6545ae82d046b3c',
        'database' => 'kkim',
        'charset' => 'utf8',
        'pool' => [
            'min' => 5,
            'max' => 100,
            'retry' => 3
        ]
    ],
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'auth' => '',
        'pool' => [
            'min' => 3,
            'max' => 6,
            'retry' => 3,
            'checkInterval' => 60,
            'keepalive' => 1800
        ]
    ],
];