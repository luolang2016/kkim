/**
 * ***模块***
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * author: YiMing
 * date: 2020-12
 * Mail: 641612700@qq.com
 */

layui.define(['jquery', 'form'], function (exports) {
    let $ = layui.jquery;

    let obj = {

        //是否为空
        isEmpty: function (str) {
            let s = String(str); //强制转换
            return s === ""
                || s.replace(/^\s*|\s*$/g, "") === ""
                || s.replace(/　/g, "") === "";
        },

        //时间戮转日期
        timestampToDate: function (ts) {
            let date = new Date(ts * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
            let Y = date.getFullYear();
            let M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1);
            let D = date.getDate();
            let h = date.getHours();
            let m = date.getMinutes();
            let s = date.getSeconds();
            //return Y+M+D+h+m+s;
            return M + '-' + D + ' ' + h + ':' + m;
        },

        captchaRefresh: function () {
            $('#captcha').click(function () {
                let ts = new Date().getTime();
                $('#captcha').attr('src', '/chat/captcha?t=' + ts);
                $('#verify_code').focus();
            });
        },

        //GET方法
        getFun: function (getData, url, callback) {
            $.ajax({
                type: "GET",
                url: url + "?t=" + Math.random(),
                data: getData,
                dataType: "json",
                success: function (res) {
                    if (parseInt(res.code) !== 1) {
                        console.log(res);
                        layer.msg(res.msg);
                    } else {
                        if (callback) {
                            callback(res.data);
                        }
                    }
                },
                error: function () {
                    layer.msg('请求出错!');
                }
            });
        },

        //公共POST方法
        postFun: function (postData, url, btn, tip, typ, callback) {
            btn.attr('disabled', 'disabled');
            //提交
            $.ajax({
                type: "POST",
                url: url + "?t=" + Math.random(),
                data: postData,
                dataType: "json",
                success: function (res) {
                    if (parseInt(res.code) !== 1) {
                        console.log(res);
                        layer.msg(res.msg);
                        btn.removeAttr('disabled');
                    } else {
                        if (callback) {
                            callback(res.data);
                        }
                        tip = _this.isEmpty(tip) ? res.msg : tip;
                        if (typ === 1) {
                            //延迟跳转
                            layer.msg(tip, {icon: 1, time: 2000, shade: 0.4}, function () {
                                location.href = res.data.url;
                            });
                        } else if (typ === 2) {
                            layer.msg(tip, {icon: 1, time: 1500, shade: 0.4}, function () {
                                location.reload();
                            });
                        } else if (typ === 3) {
                            layer.msg(tip);
                        } else {
                            //
                        }
                    }
                },
                error: function () {
                    layer.msg('请求出错!');
                    btn.removeAttr('disabled');
                }
            });
        }

    };

    let _this = obj;
    //输出接口
    exports('public', obj);
});
