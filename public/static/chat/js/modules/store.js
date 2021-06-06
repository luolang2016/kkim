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

        setLocalSess: function (usInfo, lasting) {
            //console.log(usInfo);
            if (lasting === 1) {
                localStorage.setItem('kkim-userInfo', JSON.stringify(usInfo));
            } else {
                sessionStorage.setItem('kkim-userInfo', JSON.stringify(usInfo));
            }
        },

        checkSess: function () {
            let _user = _this.getStorageItem('kkim-userInfo');
            if (_user !== null) {
                location.href = '/chat/index';
            }
        },

        checkLogin: function () {
            let _user = _this.getStorageItem('kkim-userInfo');
            if (_user === null) {
                location.href = '/chat/login';
            }
        },

        getStorageItem: function (key) {
            if (key) {
            } else {
                key = 'kkim-userInfo';
            }
            let val = sessionStorage.getItem(key);
            if (val === null) {
                val = localStorage.getItem(key);
            }
            if (val !== null) {
                return JSON.parse(val);
            }
            return val;
        },

        clearStorageItem: function () {
            sessionStorage.clear();
            localStorage.clear();
        }

    };

    let _this = obj;
    //输出接口
    exports('store', obj);
});
