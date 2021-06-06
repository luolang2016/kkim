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
    let _socket = false;

    let obj = {

        //load
        initLoad: function () {
            store.checkLogin();
            _this.setLeftMenu();
            _this.readFriend();
            $('#chatContent').attr('readOnly', 'readOnly');
            $('#sendBtn').attr('disabled', 'disabled');
        },

        readFriend: function () {
            let sData = store.getStorageItem();
            let uid = sData.id;
            pub.getFun({uid: uid}, '/chat/user/friend', function (data) {
                //console.log(data);
                let _html = '';
                $.each(data, function (i, e) {
                    _html += '<div class="chat-list-item" data-uid="' + e.id + '">';
                    _html += '    <div class="list-item-time">';
                    _html += '        <div class="pop__time"></div>';
                    _html += '    </div>';
                    _html += '    <div class="list-item-avatar"><img src="' + e.avatar + '"></div>';
                    _html += '    <div class="list-item-info">';
                    let nm = pub.isEmpty(e.nickname) ? '未设置' : e.nickname;
                    _html += '        <h3 class="item-info-nickname">' + nm + '</h3>';
                    _html += '        <p class="item-info-last-msg"></p>';
                    _html += '    </div>';
                    _html += '</div>';
                });
                $('.chat-list-scroll-content').append(_html);
                _this.operationInit();
            });
        },

        operationInit: function () {
            _this.clickInit();
            _this.wsInit();
            _this.initExit();
            _this.initSend();
        },

        initSend: function () {
            $('#sendBtn').unbind('click').click(function () {
                let msg = $('#chatContent').val();
                if (pub.isEmpty(msg)) {
                    layer.msg('不能发送空消息');
                } else {
                    let sData = store.getStorageItem();
                    let uid = sData.id;
                    let toUid = $('#selUid').val();
                    let msgData = _this.makeData('2', uid, toUid, msg);

                    let ct = new Date().getTime() / 1000;
                    let lt = $('.chat-message-dialog-clearfix').children('li:last-child').attr('data-st');
                    let t = _this.checkSendTime(ct, lt);
                    let d = {
                        'create_time': ct,
                        'sender_avatar': sData.avatar,
                        'sender_nickname': sData.nickname,
                        'content': msg
                    };
                    let h = _this.mkMineChatRecord(d, t);
                    $('.chat-message-dialog-clearfix').append(h);
                    _this.scrollBottom();

                    if (_socket !== false) {
                        _socket.send(JSON.stringify(msgData));
                        $('#chatContent').text('');
                        $('#chatContent').val('');
                    }
                }
            });
            _this.addMsgSendEvent();
        },

        initExit: function () {
            $('.chat-list-icon-exit').unbind('click').click(function () {
                if (_socket !== false) {
                    let sData = store.getStorageItem();
                    let uid = sData.id;
                    let msgData = _this.makeData('4', uid, '', '');
                    _socket.send(JSON.stringify(msgData));
                    _socket.close();
                    _socket = false;
                }
                store.clearStorageItem();
                location.href = '/chat/login';
            });
        },

        bindUsId: function () {
            let sData = store.getStorageItem();
            let uid = sData.id;
            let msgData = _this.makeData('1', uid, '', '');
            if (_socket !== false) {
                let json = JSON.stringify(msgData);
                _socket.send(json);
            }
        },

        onclose: function () {
            _socket.onclose = function (e) {
                console.log(e);
                let txt = e.reason;
                _this.setLog('断开连接=>' + txt);
            }
        },

        onmessage: function () {
            _socket.onmessage = function (msg) {
                _this.setLog('服务器消息:' + msg.data);
                //console.log(msg);
                let d = JSON.parse(msg.data);
                switch (d.type) {
                    case '2':
                    case '3':
                        let toUid = $('#selUid').val();
                        if (d.from == toUid) {
                            let ct = new Date().getTime() / 1000;
                            let lt = $('.chat-message-dialog-clearfix').children('li:last-child').attr('data-st');
                            let t = _this.checkSendTime(ct, lt);
                            let a = '', n = '';
                            $('.chat-list-item').each(function () {
                                let i = $(this);
                                let u = i.attr('data-uid');
                                if (u == d.from) {
                                    a = i.children('.list-item-avatar').children('img').attr('src');
                                    n = i.children('.list-item-info').children('.item-info-nickname').html();
                                    return false;
                                }
                            });
                            let d2 = {
                                'create_time': d.time,
                                'sender_avatar': a,
                                'sender_nickname': n,
                                'content': d.message
                            };
                            let h = _this.mkOtherChatRecord(d2, t);
                            $('.chat-message-dialog-clearfix').append(h);
                            _this.scrollBottom();
                        }
                        break;
                    case '5':
                        _this.setLog(d.message);
                        break;
                    default:
                        break;
                }
            }
        },

        onopen: function () {
            _socket.onopen = function () {
                _this.setLog('服务器连接成功');
                _this.bindUsId();
            }
        },

        wsInit: function () {
            let url = 'ws://192.168.1.192:9502';
            if (_socket === false) {
                _socket = new WebSocket(url);
                _this.onopen();
                _this.onmessage();
                _this.onclose();
            } else {
                _this.setLog('已经连接成功了，不需要再进行连接！');
            }
        },

        clearHistory: function () {
            $('.chat-message-dialog-clearfix').children('li').not('.not_remove').remove();
        },

        readChatHistory: function () {
            let sData = store.getStorageItem();
            let uid = sData.id;
            let toId = $('#selUid').val();
            pub.getFun({meid: uid, toid: toId}, '/chat/history', function (data) {
                //console.log(data);
                let _html = '';
                let lt = new Date().getTime() / 1000;
                $.each(data, function (i, e) {
                    let ct = e.create_time;
                    if (uid == e.sender_id) { //我发送的
                        if (pub.isEmpty(_html)) {
                            _html = _this.mkMineChatRecord(e);
                        } else {
                            let t = _this.checkSendTime(ct, lt);
                            _html += _this.mkMineChatRecord(e, t);
                        }
                    } else { //他人发送人
                        if (pub.isEmpty(_html)) {
                            _html = _this.mkOtherChatRecord(e);
                        } else {
                            let t = _this.checkSendTime(ct, lt);
                            _html += _this.mkOtherChatRecord(e, t);
                        }
                    }
                    lt = e.create_time;
                });
                $('.chat-message-dialog-clearfix').append(_html);
                setTimeout(function () {
                    _this.scrollBottom();
                }, 300);
            });
        },

        checkSendTime: function (ct, lt) {
            let c = ct - lt;
            if (c > 300) {
                let dt = pub.timestampToDate(ct);
                return '<li class="chat-dialog-msg-time"><span>' + dt + '</span></li>';
            } else {
                return '<li class="chat-dialog-msg-interval"><span></span></li>';
            }
        },

        mkOtherChatRecord: function (e, t) {
            let h = '';
            if (t) {
                h = t;
            }
            h += '<li class="chat-dialog-msg-others" data-st="' + e.create_time + '">';
            h += '    <a class="chat-dialog-msg-avatar"><img src="' + e.sender_avatar + '"></a>';
            h += '    <div class="chat-dialog-msg-content">';
            h += '        <p class="chat-dialog-msg-sender">' + e.sender_nickname + '</p>';
            h += '        <div class="chat-dialog-msg-msg chat-dialog-mmgg">';
            h += '            <span>' + e.content + '</span>';
            h += '        </div>';
            h += '    </div>';
            h += '</li>';
            return h;
        },

        mkMineChatRecord: function (e, t) {
            let h = '';
            if (t) {
                h = t;
            }
            h += '<li class="chat-dialog-msg-me" data-st="' + e.create_time + '">';
            h += '    <div class="chat-dialog-msg-me-content">';
            h += '        <p class="chat-dialog-msg-me-author">' + e.sender_nickname + '</p>';
            h += '        <div class="chat-dialog-msg-me-msg chat-dialog-mmgg chat-dialog-msg-me-msg-text">';
            h += '            <span>' + e.content + '</span>';
            h += '        </div>';
            h += '    </div>';
            h += '    <a class="chat-dialog-msg-avatar"><img src="' + e.sender_avatar + '"></a>';
            h += '</li>';
            return h;
        },

        clickInit: function () {
            $('.chat-list-item').unbind('click').click(function () {
                let t = $(this);
                let id = t.attr('data-uid');
                let nkm = t.children('.list-item-info').children('.item-info-nickname').html();
                let old_selUid = $('#selUid').val();
                let reRead = id != old_selUid;
                $('#selUid').val(id);
                $('.right-dialog-chat-title').html(nkm);
                $('#chatContent').removeAttr('readOnly');
                t.siblings().css('background-color', '#E7E6E5');
                t.css('background-color', '#C7C6C6');
                $('#sendBtn').removeAttr('disabled');
                if (reRead) {
                    _this.clearHistory();
                    _this.readChatHistory();
                }
            });
            $('.message-dialog-send').unbind('click').click(function () {
                $('#chatContent').focus();
            });
        },

        addMsgSendEvent: function () {
            $('#chatContent').on('keyup', function (e) {
                //console.log(e);
                if (e.ctrlKey && (e.which == 10 || e.which == 13)) {
                    $('#sendBtn').click();
                }
            });
        },

        setLeftMenu: function () {
            let c1 = $('#onChat'), c2 = $('#onContact');
            c1.unbind('click').click(function () {
                c1.attr('src', '/images/chat_ico2.png');
                c2.attr('src', '/images/contact_ico1.png');
            });
            c2.unbind('click').click(function () {
                c2.attr('src', '/images/contact_ico2.png');
                c1.attr('src', '/images/chat_ico1.png');
            });
            let sData = store.getStorageItem();
            $('.avatar-img').attr('src', sData.avatar);
            $('.avatar-img').attr('title', sData.nickname);
        },

        scrollBottom: function () {
            //chat-message-dialog-body
            if ($(".chat-message-dialog-content").height() - ($(".chat-message-dialog-body").scrollTop() + $(".chat-message-dialog-body").height()) > 10) {
                $(".chat-message-dialog-body").scrollTop($(".chat-message-dialog-content").height() + 1e3);
                setTimeout(function () {
                    $(".chat-message-dialog-body").scrollTop($(".chat-message-dialog-content").height() + 1e3)
                }, 200);
            } else {
                console.log($(".chat-message-dialog-content").height());
                console.log($(".chat-message-dialog-body").scrollTop());
                console.log($(".chat-message-dialog-body").height());
            }
        },

        makeData: function (type, from, to, message) {
            return {
                'type': type, //0心跳包，1绑定UID，私聊2，群聊3，4退出，5提示信息（接收时）
                'from': from, //发送者
                'to': to, //接收者，用户ID
                'message': message, //消息内容
            };
        },

        setLog: function (txt) {
            //$('.log').append(txt + "\r\n");
            console.log('=============' + txt + '==============');
        }

    };

    let _this = obj;
    //输出接口
    exports('chat', obj);
});
