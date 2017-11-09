var connect;
var socket = {};
socket = {
    onOpen : function(event) {
    },
    onSend : function(datas) {
        console.log('发送数据包：' + datas);
    },
    onMessage : function(datas) {
        var msg = datas;
        var messagetype = msg.data.substr(0, 1); // 获取消息第一位判断消息类型
        var data = JSON.parse(msg.data.substr(1)); // 数据转换

        // 接收消息
        if (messagetype == 2) {
            var dbMsg = data[1];
            delete dbMsg.type;
            dbMsg.time = dbMsg.mid / 8388608 + 1451577600000;
            dbMsg.hash = '';
            dbMsg.owner = window.TS.MID;
            dbMsg.read = message.datas.cid == dbMsg.cid ? 1 : 0;
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                // 消息放入本地
                window.TS.dataBase.message.put(dbMsg);
                // 修改房间最后消息时间
                window.TS.dataBase.room.where({cid: dbMsg.cid,owner: dbMsg.owner}).modify({
                    last_message_time: dbMsg.time
                })
            });
            message.setMessage(dbMsg.txt, dbMsg.uid);
        }

        // 应答消息
        if (messagetype == 3) {
            // 消息同步
            if (data[0] === 'convr.msg.sync' && data[1].length) {
                data[1].forEach((value, index) => {
                    value.time = value.mid / 8388608 + 1451577600000;
                    value.owner = window.TS.MID;
                    value.read = 1;
                    // 对比本地存储的会话，写入新会话
                    window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                        // 查找我的最后一条消息
                        window.TS.dataBase.message.where({cid: value.cid, owner: window.TS.MID}).last(item => {
                            if ((item !== undefined && value.seq > item.seq) || item === undefined) {
                                // 写入数据库
                                window.TS.dataBase.message.put(value);
                                // 修改房间最后通话时间
                                window.TS.dataBase.room.where({cid: value.cid,owner: window.TS.MID}).modify({
                                    last_message_time: value.time,
                                    last_message: value.txt
                                });
                            }
                        });
                    })
                    .catch(e => {
                        console.log(e);
                    })
                });
            }

            // 登录后同步消息
            if (data[0] === 'auth' && data[1].seqs) {
                var _message = message;

                data[1].seqs.forEach(seq => {
                    var msg = '2';
                    var message = [
                        'convr.msg.sync', {
                            "cid": parseInt(seq.cid),
                            "limit": 10,
                            "order": 1 // 倒序获取最新10条消息
                        }
                    ];
                    msg += JSON.stringify(message);
                    window.TS.webSocket.send(msg);
                });
            }

            // 接受消息
            if (data[0] === 'convr.msg') {
                // 添加到本地数据库
                var dbData = {
                    seq: data[1].seq,
                    mid: data[1].mid,
                    time: data[1].mid / 8388608 + 1451577600000,
                    owner: window.TS.MID
                };
                window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                    // 修改本地消息
                    window.TS.dataBase.message.where('hash').equals(data[2]).modify(dbData);
                    window.TS.dataBase.message.where('hash').equals(data[2]).first().then(results => {
                        // 更改房间的最后消息时间
                        window.TS.dataBase.room.where({cid: results.cid,owner: window.TS.MID}).modify({
                            last_message_time: results.time,
                            last_message: results.txt
                        });
                    });
                })
                .catch(window.TS.dataBase.ModifyError, function(e) {
                    console.error(e);
                }).catch(function(e) {
                    console.error(e);
                });
            }
        }
    },
    onError : function(event) {
        console.log('WebSocket错误');
    },
    onClose : function(event) {
        if(!window.TS.webSocket) return;
        window.TS.webSocket = null;
        console.log('WebSocket关闭：ws://' + TS.BOOT['im:serve']);
    }
};

var message = {};
message = {
    datas: {},

    init: function(datas) {
        // 获取对话列表
        message.getConversations();

        // 链接socket
        if(TS.BOOT['im:serve']) { //判断是否配置im聊天服务器
            message.connect();
        } else {
            console.log('未配置Socket地址或未登录');
        }

        // 设置未读数
        message.getUnreadMessage();
        var unread_timeout = window.setInterval(message.getUnreadCounts, 5000);
        message.getUnreadChats();
        var unread_timeout = window.setInterval(message.getUnreadChats, 1000);
    },

    connect: function() {
        // 创建本地存储
        var db = new Dexie('TS');
        db.version(1).stores({
            // message
            message: "++, owner, cid, txt, uid, hash, mid, seq, time, read",

            // room
            room: "++, owner, cid, user_id, name, pwd, type, uids, last_message, last_message_time",
        });

        window.TS.dataBase = db;

        // 存储会话列表
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            this.datas.list.forEach(val => {
                window.TS.dataBase.room.where({cid: val.cid,owner: window.TS.MID}).first(function(item){
                    if (item === undefined) {
                        val.last_message_time = 0;
                        val.last_message = '';
                        val.owner = window.TS.MID;
                        window.TS.dataBase.room.put(val);
                    } else {
                        val.last_message = item.last_message;
                    }
                });
            });
        });

        // 非连接状态及未连接状态 连接SOCKET
        if (window.TS.webSocket == null) {
            var url = '/api/v1/im/users';
            $.ajax({
                url: url,
                type: 'GET',
                success:function(res){
                    if (res.status) {
                        try {
                            window.TS.webSocket = new window.WebSocket(TS.BOOT['im:serve'] + '?token=' + res.data.im_password);
                            window.TS.webSocket.onopen = function(evt) {
                                socket.onOpen(evt);
                            }
                            window.TS.webSocket.onmessage = function(evt) {
                                socket.onMessage(evt);
                            }
                            window.TS.webSocket.onclose = function(evt) {
                                socket.onClose(evt);
                            }
                        } catch (e) {
                            window.console.log(e);
                        }
                    } else {
                        console.log('获取聊天授权失败');
                    }
                }
            }, 'json');
        } else if (window.TS.webSocket && window.TS.webSocket.readyState != 1) {
            try {
                window.TS.webSocket = new window.WebSocket(TS.BOOT['im:serve']);
                window.TS.webSocket.onopen = function(evt) {
                    socket.onOpen(evt);
                }
                window.TS.webSocket.onmessage = function(evt) {
                    socket.onMessage(evt);
                }
                window.TS.webSocket.onclose = function(evt) {
                    socket.onClose(evt);
                }
            } catch (e) {
                window.console.log(e);
            }
        }
    },

    // 获取聊天对话列表
    getConversations: function() {
        var _this = this;
        $.ajax({
            url: TS.API + '/im/conversations/list/all',
            async: false,
            type: 'GET',
            success: function(res) {
                var uids = [];
                for(var i in res) {
                    // 最多50个会话
                    if (i > 49) break;
                    var _uids = res[i]['uids'].split(',');
                    for (var j in _uids) {
                        if (_uids[j] != TS.MID) {
                            uids.push(_uids[j]);
                            res[i]['other_uid'] = parseInt(_uids[j]);
                        }
                    }
                }

                // 获取对话中其他用户用户信息
                var users = getUserInfo(uids.join(','));
                var _users = [];
                for (var l in users) {
                    _users[users[l]['id']] = users[l];
                }
                _this.datas.list = res;
                _this.datas.users = _users;

                // 设置聊天对话
                for (var k in res) {
                    message.setConversation(0, res[k], _users[res[k]['other_uid']]);
                }
            }
        }, 'json');
    },


    // 设置会话
    setConversation: function(type, room) {
        // 设置侧边栏
        if (type != 1) {
            var sidehtml = '<dd id="ms_chat_' + room.cid + '"><a href="javascript:;" onclick="openChatDialog(this, 5, '+ room.cid +')"><img src="' + getAvatar(this.datas.users[room.other_uid], 50) + '"/></a></dd>';

            $('#ms_fixed').append(sidehtml);
        } 

        // 设置弹出框
        if (type != 0) {
            var css = '';
            if (room.cid == this.datas.cid) {
                css = 'class="current_room"';
                message.listMessage(room.cid);
            }

            var last_message = room.last_message == undefined ? '' : room.last_message;

            var html = '<li ' + css + ' class="room_item" data-type="5" data-cid="' + room['cid'] + '" id="chat_' + room['cid'] + '">'
                        +      '<div class="chat_left_icon">'
                        +          '<img src="' + getAvatar(this.datas.users[room.other_uid]) + '" class="chat_svg">'
                        +       '</div>'
                        +      '<div class="chat_item">'
                        +          '<span class="chat_span">' + this.datas.users[room.other_uid]['name'] + '</span>'
                        +          '<div>' + last_message + '</div>'
                        +      '</div>'
                        +  '</li>';
            $('#root_list').append(html);
        }
    },

    listMessage: function(cid) {
        var _this = this;
        // 设置房间名
        $('#chat_wrap .body_title').html();
        $('#chat_wrap .clickMore').remove();
        $('#chat_cont').html('');

        // 查询消息
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
            window.TS.dataBase.message
                .orderBy('seq')
                .filter( (item) => {
                    return (item.seq != -1 && item.cid === cid);
                })
                .limit(15)
                .reverse()
                .toArray( array => {
                    var messageList = [];
                    var messageBody = {};
                    if(array.length) {
                        array = array.reverse();
                        array.forEach((value) => {
                            message.setMessage(value.txt, value.uid);
                        });
                    }
                });
        });
    },

    sendMessage: function(cid) {
        var msg = '2';
        var time = (new Date()).getTime();
        var hash = time + '_'  + TS.MID;
        var txt = $('#chat_text').val();
        var message_one = [
            'convr.msg',
            {
            "cid": cid, // 对话id
            "type": 0, // 消息的类型，私密消息
            "txt": txt, // 消息的文本内容，字符串，可选，默认空字符串
            "rt": false, // 非实时消息
            },
            hash
        ];
        msg += JSON.stringify(message_one);
        if(!window.TS.webSocket) {
            console.log('链接出错');
            return false;
        }

        if(window.TS.webSocket.readyState != 1) {
            message.connect();

            connect = window.setTimeout(function(){
                if(window.TS.webSocket.readyState == 1) {
                    message.sendMessage(cid);
                }
            },1000);
        } else {
            clearTimeout(connect);
            window.TS.webSocket.send(msg);
            var dbMsg = {
                    cid: cid,
                    uid: window.TS.MID,
                    txt: txt,
                    hash: hash,
                    mid: 0,
                    seq: -1,
                    time: 0,
                    owner: window.TS.MID
                };
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
                window.TS.dataBase.message.put(dbMsg);
            })
            .catch (function (e) {
                console.error(e);
            });

            message.setMessage(txt, window.TS.MID);
        }

    },

    // 设置消息
    setMessage: function(txt, user_id){
        $('#chat_text').val('');

        if (user_id != window.TS.MID) {
            html = '<div class="chatC_left">'
                 +      '<img src="' + getAvatar(this.datas.users[user_id]) + '" class="chat_avatar">'
                 +      '<span class="chat_left_body">' + txt + '</span>'
                 + '</div>';
        } else {
            html = '<div class="chatC_right">'
                 +      '<img src="' + getAvatar(TS.USER) + '" class="chat_avatar fr">'
                 +      '<span class="chat_right_body">' + txt + '</span>'
                 + '</div>';
        }
        $('#chat_cont').append(html);
        var div = document.getElementById('chat_scroll');
        div.scrollTop = div.scrollHeight;
    },

    // 获取未读消息数量
    getUnreadMessage: function() {
        // 获取未读通知数量
        $.ajax({
            url: TS.API + '/user/notifications',
            type: 'HEAD',
            success: function(data, status, request) {
                TS.UNREAD.notifications = request.getResponseHeader('unread-notification-limit');

                message.setUnreadMessage();
            }
        }, 'json');

        // 获取未读点赞，评论，审核通知数量
        $.ajax({
            url: TS.API + '/user/unread-count',
            type: 'GET',
            success: function(res) {
                TS.UNREAD.comments = res.counts.unread_comments_count ? res.counts.unread_comments_count : 0;
                TS.UNREAD.likes = res.counts.unread_likes_count ? res.counts.unread_likes_count : 0;

                // 审核通知数量
                var pinneds_count = 0;
                for(var i in res.pinneds){
                    pinneds_count += res.pinneds[i]['count'];
                }
                TS.UNREAD.pinneds = pinneds_count;

                message.setUnreadMessage();
            }
        }, 'json');

    },

    // 获取未读聊天消息数量
    getUnreadChats: function() {
        // 获取未读消息数量
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, window.TS.dataBase.message, () => {
            window.TS.dataBase.room.where({owner: window.TS.MID}).each( value => {
                window.TS.dataBase.message.where({read: 0, cid: value.cid}).count( number => {
                    if (number > 0) {
                        message.setUnreadChat(value.cid, number);
                    }
                });
            });
        })
        .catch(e => {
            console.log(e);
        });
    },

    // 设置未读消息数量
    setUnreadMessage: function(){
        for (var i in TS.UNREAD) {
            if (TS.UNREAD[i] > 0) {
                $('#ms_' + i + ' .unread_div').remove();
                $('#ms_' + i).prepend(message.formatUnreadHtml(0, TS.UNREAD[i]));
                if ($('.chat_dialog').length > 0) {
                    $('#chat_' + i + ' .chat_unread_div').remove();
                    $('#chat_' + i + ' .chat_left_icon').prepend(message.formatUnreadHtml(1, TS.UNREAD[i]));
                }
            }
        }
    },

    // 设置未读聊天消息数量
    setUnreadChat: function(cid, value){
        $('#ms_chat_' + cid + ' .unread_div').remove();
        $('#ms_chat_' + cid).prepend(message.formatUnreadHtml(0, value));
        if ($('.chat_dialog').length > 0) {
            $('#chat_' + cid + ' .chat_unread_div').remove();
            $('#chat_' + cid + ' .chat_left_icon').prepend(message.formatUnreadHtml(1, value));
        }
    },

    // 获取未读消息数量html
    formatUnreadHtml: function(type, value){
        if (type == 0) {
            var html = '<div class="unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        } else {
            var html = '<div class="chat_unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        }
        return html;
    }
}
