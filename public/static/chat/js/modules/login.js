/**
 * ***模块***
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * author: YiMing
 * date: 2020-12
 * Mail: 641612700@qq.com
 */

layui.define(['jquery', 'form', 'public', 'store'], function (exports) {
    let $ = layui.jquery;
    let form = layui.form;
    let pub = layui.public;
    let store = layui.store;

    let obj = {

        //初始化添加方法
        initLoad: function () {
            store.checkSess();
            _this.customerVerify();
            _this.listenToSubmit();
            pub.captchaRefresh();
        },

        //自定义验证规则
        customerVerify: function () {
            form.verify({
                account: [/^(?!_)(?![0-9])(?!.*?_$)[a-zA-Z0-9_]{5,20}$/i, '请正确输入您的登录账号.'],
                password: function (value) {
                    if (pub.isEmpty(value) || value.length < 5) {
                        return '密码有误.';
                    }
                },
                verifyCode: [/^[a-zA-Z0-9]{4,6}$/i, '请输入正确的验证码']
            });
        },

        //监听表单提交
        listenToSubmit: function () {
            form.on('submit(login)', function (data) {
                pub.postFun(data.field, '/chat/login/post', $('#login'), '', 1, function (uData) {
                    store.setLocalSess(uData.us_info);
                });
                return false;
            });
        }

    };

    let _this = obj;
    //输出接口
    exports('login', obj);
});
