<?php

namespace app\common\utils;

class Redirect
{

    /**
     * Phalcon本身的跳转不理想，这个方法可直接跳转
     * @param string $url 要跳转的URL
     */
    public static function redirectTo($url)
    {
        header('Location:' . $url, TRUE);
        exit();
    }

    /**
     * script方式跳转无提示信息，$top为1时以top的方式跳转
     * @param string $url 要跳转的URL
     * @param int $top top为1时以top的方式跳转
     */
    public static function redirectToByJs($url, $top = 0)
    {
        $isTop = $top === 1 ? 'top.' : '';
        echo '<script>' . $isTop . 'location.href="' . $url . '";</script>';
        exit();
    }

    /**
     * 直接以TOP方式跳转
     * @param string $url 要跳转的URL
     */
    public static function redirectToByTop($url)
    {
        echo '<script>top.location.href="' . $url . '";</script>';
        exit();
    }

    /**
     * script方式跳转有提示信息，$top为1时以top的方式跳转
     * @param string $url 要跳转的URL
     * @param string $message 提示信息
     * @param int $top top为1时以top的方式跳转
     */
    public static function redirectToAlert($url, $message, $top = 0)
    {
        $isTop = $top === 1 ? 'top.' : '';
        echo '<script>alert("' . $message . '");' . $isTop . 'location.href="' . $url . '";</script>';
        exit();
    }

    /**
     * script方式后退无提示
     */
    public static function historyGoBack()
    {
        echo '<script>history.go(-1);</script>';
        exit();
    }

    /**
     * script方式后退有提示
     * @param string $message 提示信息
     */
    public static function historyGoBackAlert($message)
    {
        echo '<script>alert("' . $message . '");history.go(-1);</script>';
        exit();
    }

}
