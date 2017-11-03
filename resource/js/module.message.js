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
            dbMsg.addCount = true;
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                // 消息放入本地
                window.TS.dataBase.message.put(dbMsg);
                // 修改房间最后消息时间
                window.TS.dataBase.room.where('[cid+owner]').equals([dbMsg.cid, dbMsg.owner]).modify({
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
                    // 对比本地存储的会话，写入新会话
                    window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                        // 查找我的最后一条消息
                        window.TS.dataBase.message.where('[cid+owner]').equals([value.cid, window.TS.MID]).last(item => {
                            if ((item !== undefined && value.seq > item.seq) || item === undefined) {
                                // 写入数据库
                                window.TS.dataBase.message.put(value);
                                // 修改房间最后通话时间
                                window.TS.dataBase.room.where('[cid+owner]').equals([value.cid, window.TS.MID]).modify({
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
                        window.TS.dataBase.room.where('[cid+owner]').equals([results.cid, window.TS.MID]).modify({
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
        return false;
        this.datas.list = datas.list;
        this.datas.users = datas.users;
        this.datas.cid = datas.cid;

        // 链接socket
        if(TS.BOOT['im:serve'] && TS.MID != 0) { //判断是否配置im聊天服务器
            message.connect();
        } else {
            console.log('未配置Socket地址或未登录');
        }
    },

    connect: function() {
        if (!TS.BOOT['im:serve']) return false;

        // 创建本地存储
        var db = new Dexie('TS');
        db.version(2).stores({
            // message
            message: "++, owner, cid, txt, uid, hash, mid, seq, time, read, [cid+mid], [cid+owner]",

            // room
            room: "++, owner, cid, user_id, name, pwd, type, uids, last_message, last_message_time, [cid+owner]",
        });

        window.TS.dataBase = db;

        // 存储会话列表
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {

            this.datas.list.forEach(val => {
                window.TS.dataBase.room.where('[cid+owner]').equals([val.cid, window.TS.MID]).first(function(item){
                    if (item === undefined) {
                        val.last_message_time = 0;
                        val.last_message = '';
                        val.owner = window.TS.MID;
                        window.TS.dataBase.room.put(val);
                    } else {
                        val.last_message = item.last_message;
                    }
                });

                // 设置对话列表
                message.setConversation(val);
            });

            var div = document.getElementById('chat_left_scroll');
            console.log($(".current_room").position().top);
            div.scrollTop = $(".current_room").offset().top;

            return false;

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

    // 设置会话
    setConversation: function(room) {
        var css = '';
        if (room.cid == this.datas.cid) {
            css = 'class="current_room"';
            message.listMessage(room.cid, this.datas.cid);
        }

        var last_message = room.last_message == undefined ? '' : room.last_message;

        var html = '<li ' + css + ' class="room_item" data-type="5" data-cid="' + room['cid'] + '">'
                    +      '<div class="chat_left_icon">'
                    +          '<img src="' + getAvatar(this.datas.users[room.other_uid]) + '" class="chat_svg">'
                    +       '</div>'
                    +      '<div class="chat_item">'
                    +          '<span class="chat_span">' + this.datas.users[room.other_uid]['name'] + '</span>'
                    +          '<div>' + last_message + '</div>'
                    +      '</div>'
                    +  '</li>';
        $('#root_list').append(html);
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
    }
}
