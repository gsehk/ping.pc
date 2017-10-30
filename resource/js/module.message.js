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
        console.log(msg);
        var messagetype = msg.data.substr(0, 1); // 获取消息第一位判断消息类型
        var data = JSON.parse(msg.data.substr(1)); // 数据转换

        // 接收消息
        if (messagetype == 2) {
            var dbMsg = data[1];
            delete dbMsg.type;
            dbMsg.time = dbMsg.mid / 8388608 + 1451577600000;
            dbMsg.hash = '';
            dbMsg.owner = window.TS_WEB.currentUserId;
            dbMsg.addCount = true;
            window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.messagebase, window.TS_WEB.dataBase.chatroom, () => {
                // 消息放入本地
                window.TS_WEB.dataBase.messagebase.put(dbMsg);
                // 修改房间最后消息时间
                window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([dbMsg.cid, dbMsg.owner]).modify({
                    last_message_time: dbMsg.time
                })
            });
            message.setMessage(dbMsg.txt, dbMsg.cid, dbMsg.uid);
        }

        // 应答消息
        if (messagetype == 3) {
            // 消息同步
            if (data[0] === 'convr.msg.sync' && data[1].length) {
                data[1].forEach((value, index) => {
                    value.time = value.mid / 8388608 + 1451577600000;
                    value.owner = window.TS_WEB.currentUserId;
                    // 对比本地存储的会话，写入新会话
                    window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.messagebase, window.TS_WEB.dataBase.chatroom, () => {
                        // 查找我的最后一条消息
                        window.TS_WEB.dataBase.messagebase.where('[cid+owner]').equals([value.cid, window.TS_WEB.currentUserId]).last(item => {
                            if (item !== undefined) {
                                if (value.seq > item.seq) {
                                    // 写入数据库
                                    window.TS_WEB.dataBase.messagebase.put(value);
                                    // 修改房间最后通话时间
                                    window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([value.cid, window.TS_WEB.currentUserId]).modify({
                                        last_message_time: value.time
                                    });
                                    window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([value.cid, window.TS_WEB.currentUserId]).first().then(items => {
                                        if (items !== undefined) {
                                            var uids = items.uids.split(',');
                                            var user_id = 0;
                                            if (uids[0] == window.TS_WEB.currentUserId) {
                                                user_id = uids[1];
                                            } else {
                                                user_id = uids[0];
                                            }
                                        }
                                    });
                                }
                            } else {
                                // 写入数据库
                                window.TS_WEB.dataBase.messagebase.put(value);
                                // 更新时间
                                window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([value.cid, window.TS_WEB.currentUserId]).modify({
                                    last_message_time: value.time
                                });
                                window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([value.cid, window.TS_WEB.currentUserId]).first().then(items => {
                                    if (items !== undefined) {
                                        var uids = items.uids.split(',');
                                        var user_id = 0;
                                        if (uids[0] == window.TS_WEB.currentUserId) {
                                            user_id = uids[1];
                                        } else {
                                            user_id = uids[0];
                                        }
                                    }
                                })
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
                    window.TS_WEB.webSocket.send(msg);
                });
            }

            // 接受消息
            if (data[0] === 'convr.msg') {
                // 添加到本地数据库
                var dbData = {
                    seq: data[1].seq,
                    mid: data[1].mid,
                    time: data[1].mid / 8388608 + 1451577600000,
                    owner: window.TS_WEB.currentUserId
                };
                window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.messagebase, window.TS_WEB.dataBase.chatroom, () => {
                    // 修改本地消息
                    window.TS_WEB.dataBase.messagebase.where('hash').equals(data[2]).modify(dbData);
                    window.TS_WEB.dataBase.messagebase.where('hash').equals(data[2]).first().then(results => {
                        // 更改房间的最后消息时间
                        window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([results.cid, window.TS_WEB.currentUserId]).modify({
                            last_message_time: results.time
                        });
                    });
                })
                .catch(window.TS_WEB.dataBase.ModifyError, function(e) {
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
        if(!window.TS_WEB.webSocket) return;
        window.TS_WEB.webSocket = null;
        console.log('WebSocket关闭：ws://' + SOCKET_URL);
    }
};

var message = {};
message = {
    datas: {},

    init: function(datas) {
        this.datas.list = datas.list;
        this.datas.users = datas.users;
        this.datas.cid = datas.cid;

        // 链接socket
        if(SOCKET_URL && MID != 0) { //判断是否配置im聊天服务器
            message.connect();

            // 若有cid，创建对话信息
            if (this.datas.cid != 0) message.listMessage(this.datas.cid);
        } else {
            console.log('未配置Socket地址或未登录');
        }
    },

    connect: function() {
        if (!SOCKET_URL) return false;

        // 创建本地存储
        var db = new Dexie('ThinkSNS');
        db.version(2).stores({
            // ImMessage
            messagebase: "++, txt, cid, uid, hash, mid, seq, time, owner, [cid+mid], [cid+owner]",

            // chatroom
            chatroom: "++, cid, user_id, name, pwd, type, uids, last_message_time, owner, [cid+owner], newMessage",
        });

        window.TS_WEB.dataBase = db;
        window.TS_WEB.currentUserId = MID;

        // 存储会话列表
        window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.chatroom, () => {
            $.each(this.datas.list, function(key, val){
                window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([val.cid, window.TS_WEB.currentUserId ]).count( number => {
                    if(!number > 0) {
                        val.last_message_time = 0;
                        val.owner = window.TS_WEB.currentUserId;
                        // 将对话列表写入到本地数据库
                        delete(val.user);
                        window.TS_WEB.dataBase.chatroom.put(val);
                    }
                });
            })

        });

        // 非连接状态及未连接状态 连接SOCKET
        if (window.TS_WEB.webSocket == null) {
            var url = '/api/v1/im/users';
            $.ajax({
                url: url,
                type: 'GET',
                success:function(res){
                    if (res.status) {
                        try {
                            window.TS_WEB.webSocket = new window.WebSocket(SOCKET_URL + '?token=' + res.data.im_password);
                            window.TS_WEB.webSocket.onopen = function(evt) {
                                socket.onOpen(evt);
                            }
                            window.TS_WEB.webSocket.onmessage = function(evt) {
                                socket.onMessage(evt);
                            }
                            window.TS_WEB.webSocket.onclose = function(evt) {
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
        } else if (window.TS_WEB.webSocket && window.TS_WEB.webSocket.readyState != 1) {
            try {
                window.TS_WEB.webSocket = new window.WebSocket(SOCKET_URL);
                window.TS_WEB.webSocket.onopen = function(evt) {
                    socket.onOpen(evt);
                }
                window.TS_WEB.webSocket.onmessage = function(evt) {
                    socket.onMessage(evt);
                }
                window.TS_WEB.webSocket.onclose = function(evt) {
                    socket.onClose(evt);
                }
            } catch (e) {
                window.console.log(e);
            }
        }
    },

    listMessage: function(cid) {
        var _this = this;

        // 设置房间名
        $('#chat_wrap .body_title').html(_this.datas.users[cid]['name']);
        $('#chat_wrap .clickMore').remove();

        var div = document.getElementById('chat_scroll');
        // 查询消息
        window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.messagebase, () => {
            window.TS_WEB.dataBase.messagebase
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
                    $('#chat_cont').html('');
                    $.each(array, function(key, value){
                        message.setMessage(value.txt, cid, value.uid);
                    })
                  }
                });
        });
    },

    sendMessage: function(cid) {
        var msg = '2';
        var time = (new Date()).getTime();
        var hash = time + '_'  + this.datas.users[cid]['id'];
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
        if(!window.TS_WEB.webSocket) {
            console.log('链接出错');
            return false;
        }

        if(window.TS_WEB.webSocket.readyState != 1) {
            message.connect();

            connect = window.setTimeout(function(){
                if(window.TS_WEB.webSocket.readyState == 1) {
                    message.sendMessage(cid);
                }
            },1000);
        } else {
            clearTimeout(connect);
            window.TS_WEB.webSocket.send(msg);
            var dbMsg = {
                    cid: cid,
                    uid: window.TS_WEB.currentUserId,
                    txt: txt,
                    hash: hash,
                    mid: 0,
                    seq: -1,
                    time: 0,
                    owner: window.TS_WEB.currentUserId
                };
            window.TS_WEB.dataBase.transaction('rw?', window.TS_WEB.dataBase.messagebase, () => {
                window.TS_WEB.dataBase.messagebase.put(dbMsg);
            })
            .catch (function (e) {
                console.error(e);
            });

            message.setMessage(txt, cid, window.TS_WEB.currentUserId);
        }

    },

    // 设置消息
    setMessage: function(txt, cid, user_id){
        $('#chat_text').val('');

        if (user_id != window.TS_WEB.currentUserId) {
            var other_avatar = this.datas.users[cid]['avatar'] != null ? this.datas.users[cid]['avatar'] : DEFAULT_AVATAR;
            html = '<div class="chatC_left">'
                 +      '<img src="' + other_avatar + '" class="chat_avatar">'
                 +      '<span class="chat_left_body">' + txt + '</span>'
                 + '</div>';
        } else {
            html = '<div class="chatC_right">'
                 +       '<img src="' + AVATAR + '" class="chat_avatar fr">'
                 +       '<span class="chat_right_body">' + txt + '</span>'
                 +  '</div>';
        }
        $('#chat_cont').append(html);
        var div = document.getElementById('chat_scroll');
        div.scrollTop = div.scrollHeight;
    }
}
