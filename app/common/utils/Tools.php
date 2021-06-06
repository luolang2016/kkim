<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\common\utils;

class Tools
{

    const NUMBERS = 1;
    const CAPITAL_LETTERS = 2;
    const LOWERCASE_LETTERS = 3;
    const CAPITAL_LETTERS_NUMBERS = 4;
    const LOWERCASE_LETTERS_NUMBERS = 5;
    const LETTERS = 6;
    const LETTERS_NUMBERS = 7;

    /**
     * 获取客户端IP
     * @return string
     */
    public static function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $online_ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $online_ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $online_ip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $online_ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $online_ip = "unknown";
        }

        return $online_ip;
    }

    /**
     * 生成指定长度的随机字符串
     * @param int $length 生成多少位的长度
     * @param int $flag 生成方式（1-纯数字，2-大写字母，3-小写字母，4-大写字母和数字，5-小写字母和数字，6-大写字母和小写字母，7-大小写字母和数字，其他默使用7）
     * @param bool $zero_begin 对于有数字的串，是否允许0开头
     * @return bool
     */
    public static function getRandChar($length = 6, $flag = self::LETTERS_NUMBERS, $zero_begin = TRUE)
    {
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        switch ($flag) {
            case 1:
                $str = '0123456789';
                break;
            case 2:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 3:
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 4:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
            case 5:
                $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 6:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 7:
                $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $len = strlen($str);
        $restring = '';
        for ($i = 0; $i < $length; $i++) {
            $restring .= self::subString($str, mt_rand(0, $len - 1), 1);
            if (FALSE === $zero_begin && $i === 0) {
                while ($restring == '0') {
                    $restring = self::regainChar($str);
                }
            }
        }

        return $restring;
    }

    /**
     * 截取字符串
     * @param string $in_str 要截取的字符串
     * @param int $start 起始位置
     * @param mixed $length 要截取的长度
     * @param string $encoding 字符编码
     * @return False|string
     */
    public static function subString($in_str, $start, $length = FALSE, $encoding = 'utf-8')
    {
        if (is_array($in_str) || is_object($in_str)) {
            return FALSE;
        }
        if (function_exists('mb_substr')) {
            return mb_substr($in_str, intval($start), ($length === FALSE ? self::absLength($in_str) : intval($length)), $encoding);
        } else {
            return substr($in_str, $start, ($length === FALSE ? self::absLength($in_str) : intval($length)));
        }
    }

    /**
     * 获得字符串的长度,绝对长度,中文只算一个字符,此方法仅支持UTF8编码
     * @param string $in_str 要计算长度的字符串
     * @return int
     */
    public static function absLength($in_str)
    {
        if ((empty($in_str) && $in_str != '0') || is_array($in_str) || is_object($in_str)) {
            return 0;
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($in_str);
        } else {
            preg_match_all("/./iu", $in_str, $ar);
            return count($ar[0]);
        }
    }

    /**
     * 专用的，使用getRandChar方法获取随机字符串时，若不允许0开头，则重新获取
     * @param string $str
     * @return string
     */
    private static function regainChar($str)
    {
        $len = strlen($str);
        $reStr = self::subString($str, mt_rand(0, $len - 1), 1);
        return $reStr;
    }

    /**
     * 清除单引号
     */
    public static function clearQuotes($string)
    {
        $string = str_replace("'", '', $string);
        return $string;
    }

    /**
     * 创建目录，传入的路径必须是绝对路径，参数二为目录权限如：0777
     */
    public static function createFolders($dir, $us = '', $pwr = null)
    {
        //return is_dir($dir) or (self::createFolders(dirname($dir), $pwr) and mkdir($dir, $pwr));
        if (is_dir($dir)) {
            return true;
        }
        self::createFolders(dirname($dir), $pwr);
        mkdir($dir); //在这里加权限的话貌似会有一些问题，用下方的设置权限才会有效
        //先修改用户及组
        if (!empty($us)) {
            chown($dir, $us);
            chgrp($dir, $us);
        }
        //同时，chmod方法设置权限时，权限值不要加引号，否则会无效！！！
        if ($pwr === null) {
            chmod($dir, 0777);
        } else {
            chmod($dir, $pwr);
        }
        return true;
    }

    /**
     * 是否https协议
     * @return bool
     */
    public static function isHttps()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

    /**
     * 获取当前完整URL
     */
    public static function getFullUrl()
    {
        $http = self::isHttps() ? 'https://' : 'http://';
        //$url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] .'?' . $_SERVER['QUERY_STRING']; //这样得到的是包含index.php的最原始的URL
        $url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //这样得到的才是地址栏上显示的URL
        return $url;
    }

    /**
     * 获得域名，包含http，最后不含斜杠“/”
     */
    public static function getDomainUrl()
    {
        $http = self::isHttps() ? 'https://' : 'http://';
        $url = $http . $_SERVER['HTTP_HOST'];
        return $url;
    }

    /**
     * 获得今天是星期几
     */
    public static function getWeek()
    {
        $w = date('w'); //得到数字如1，2，3；注意：星期天是0
        $weekArr = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
        return [
            'num' => $w,
            'cn' => $weekArr[$w]
        ];
    }

    /**
     * 清除post数组数据中的单引号
     * @param array $params post数组数据
     * @param array $excludeKeys 要排队的键名数组
     * @return mixed
     */
    public static function postDataClearQuotes($params, $excludeKeys = [])
    {
        $ckArr = [];
        foreach ($params as $key => $value) {
            if ($key != '_url') {
                if (!empty($excludeKeys)) {
                    if (!in_array($key, $excludeKeys)) {
                        if (gettype($params[$key]) == 'string') {
                            $ckArr[$key] = self::clearQuotes($value);
                        } else {
                            $ckArr[$key] = $value;
                        }
                    }
                } else {
                    if (gettype($params[$key]) == 'string') {
                        $ckArr[$key] = self::clearQuotes($value);
                    } else {
                        $ckArr[$key] = $value;
                    }
                }
            }
        }
        return $ckArr;
    }

}
