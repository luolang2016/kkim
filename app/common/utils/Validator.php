<?php

namespace app\common\utils;

class Validator
{

    /**
     * 输入串必须是数字
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isNumber($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^\d+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母,包括小写和大写
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isLetter($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[a-zA-Z]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母或与数字的组合,包括小写和大写
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isLetterAndNumber($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母或逗号或与数字的组合,包括小写和大写
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isLetterCommaAndNumber($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[a-zA-Z0-9,]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母或与数字/横杠（减号）的组合,包括小写和大写
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isLetterMinusSignNumber($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[a-zA-Z0-9\-]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母或与数字/横杠（减号）/下划线的组合,包括小写和大写
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isLetterMinusSignUnderlineNumber($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 输入串必须是字母,数字,下划线或其组合,不能纯数字,下划线不能开头和结尾
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function isUserName($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^(?!_)(?![0-9])(?!.*?_$)[a-zA-Z0-9_]+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 中国的电信电话号码,带区号在前面的话，区号和号码之间必须要有横杠符号“-”
     */
    public static function isTel($string)
    {
        if (!preg_match('/^\d{3}-?\d{8}$|^\d{4}-?\d{7}$|^\d{4}-?\d{8}$|^\d{7,8}$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 验证是否中国的手机号码
     */
    public static function isMobile($string)
    {
        if (!preg_match('/^[1-2][0-9]\d{9}$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 手机号前N后N的格式验证
     */
    public static function prefixNthenN($string)
    {
        $flag = true;
        if (!preg_match('/^\d{1,9}[\*]{1,9}\d{1,9}$/i', $string)) {
            $flag = false;
        }
        if (strlen($string) != 11) {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 手机号前N后N的格式验证
     */
    public static function prefixNthenNMulti($string)
    {
        $flag = true;
        if (!preg_match('/^(\d{1,9}[\*\-]{1,9}\d{1,9}[\,\，\s]+)*\d{1,9}[\*\-]{1,9}\d{1,9}$/i', $string)) {
            $flag = false;
        }
        if (strlen($string) != 11) {
            $flag = false;
        }
        return $flag;
    }

    /**
     * 电子邮件
     */
    public static function isEmail($string, $minLen = 0, $maxLen = 0)
    {
        if (!preg_match('/^[A-Za-z0-9\-_]+@[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)+$/i', $string)) {
            return FALSE;
        }
        return self::checkStringLen($string, $minLen, $maxLen);
    }

    /**
     * 验证字符串长度是否在指定范围内
     * @param string $string 输入串
     * @param int $minLen 最小长度
     * @param int $maxLen 最大长度
     * @return bool
     */
    public static function checkStringLen($string, $minLen = 0, $maxLen = 0)
    {
        $len = Tools::absLength($string);
        if ($minLen > 0) {
            if ($len < $minLen) {
                return FALSE;
            }
        }
        if ($maxLen > 0) {
            if ($len > $maxLen) {
                return FALSE;
            }
        }
        return TRUE;
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
     * 检测是否是JSON格式的字符串，是JSON格式的话直接解码返回
     */
    public static function isJson($string)
    {
        $js = json_decode($string);
        return is_null($js) ? false : $js;
    }

    /**
     * 验证是否十进制数，只验证格式，不限长度
     */
    public static function isDecimal($string)
    {
        if (!preg_match('/^(0|([1-9]\d*))(\.\d+)?$/i', $string)) {
            return FALSE;
        }
        return true;
    }

    /**
     * 验证是否金额，限最多两位小数
     */
    public static function isMoney($string)
    {
        if (!preg_match('/^(0|([1-9]\d*))(\.\d{1,2})?$/i', $string)) {
            return FALSE;
        }
        return true;
    }

    /**
     * 日期时间格式，必须是：2013-9-6 12:33:45这样的格式
     */
    public static function isDatetime($string)
    {
        if (!preg_match('/^(((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-)) (20|21|22|23|[0-1]?\d):[0-5]?\d:[0-5]?\d)$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 验证串是否是以半角逗号相隔的整数数字串
     */
    public static function isIntIds($string)
    {
        if (!preg_match('/^(?:\d+\,)*\d+$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 验证串是否是以半角逗号相隔的整数数字串，数字串本身长度限制1至9位
     */
    public static function isIntIdsLen($string)
    {
        if (!preg_match('/^(?:\d{1,9}\,)*\d{1,9}$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 验证串是否是以半角逗号相隔的整数数字串，数字串本身长度限制1位
     */
    public static function isIntIdsLenOne($string)
    {
        if (!preg_match('/^(?:\d{1}\,)*\d{1}$/i', $string)) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 专用的，验证彩种开奖时间格式的方法
     */
    public static function checkSplitTime($string)
    {
        if (!self::isTimeFormat($string)) {
            $flag = true;
            $a = explode('#', $string);
            foreach ($a as $value) {
                if (empty($value)) {
                    $flag = false;
                    break;
                }
                $tmp = explode(',', $value);
                if (!self::isTimeFormat($tmp[0]) || !self::isTimeFormat($tmp[1])) {
                    $flag = false;
                    break;
                }
            }
            return $flag;
        }
        return true;
    }

    /**
     * 是否时间格式
     */
    public static function isTimeFormat($string)
    {
        $s = '2020-05-20 ' . $string;
        return self::isDatetime($s) ? true : false;
    }

    /**
     * 是否为空字符串，或不低于指定长度的字符串
     */
    public static function isEmpty($string, $len = 0)
    {
        if (empty($string) && $string != '0') {
            return true;
        }
        if ($len > 0) {
            $l = Tools::absLength($string);
            if ($l < $len) {
                return true;
            }
        }
        return false;
    }

    /**
     * 两个时间对比,是否跨越天数,返回true则跨越了天数,返回false则未跨越
     */
    public static function ifTimeStrideAcrossDay($time1, $time2)
    {
        $d1 = date('Y-m-d', $time1);
        $d2 = date('Y-m-d', $time2);
        return $d1 != $d2;
    }

}
