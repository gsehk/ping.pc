var webSocket = null;
var options = {
    onOpen : function(event) {
    },
    onSend : function(message) {
        // console.log('发送数据包：' + message);
    },
    onMessage : function(message) {
        var msg = message;
        // console.log(msg);
        var messagetype = msg.substr(0, 1); // 获取消息第一位判断消息类型
        var data = JSON.parse(msg.substr(1)); // 数据转换

        // 接收消息
        if (messagetype == 2) {
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
                    webSocket.send(msg);
                });
            }

            // 
            if (data[0] === 'convr.msg') {
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
                        // 提交到vuex
                        // app.$store.dispatch(TOTALMESSAGELIST, cb => {
                        //     cb(results);
                        // });
                        // 更改房间的最后消息时间
                        window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([results.cid, window.TS_WEB.currentUserId]).modify({
                            last_message_time: results.time
                        });
                    });
                })
                .catch(window.TS_WEB.dataBase.ModifyError, function(e) {
                    // ModifyError did occur
                    console.error(e.failures.length + " items failed to modify");
                }).catch(function(e) {
                    console.error("Generic error: " + e);
                });
            }
        }
    },
    onError : function(event) {
        console.log('WebSocket错误：view console');
    },
    onClose : function(event) {
        if(!webSocket) return;
        webSocket = null;
        console.log('WebSocket关闭：ws://' + SOCKET_URL);
    }
};

var message = {
    init: function(chat_list, cid) {
        // 链接socket
        if(SOCKET_URL && MID != 0) { //判断是否配置im聊天服务器
            // 非连接状态及未连接状态 连接SOCKET
            if ((webSocket && webSocket.socket.readyState != 1) || webSocket == null) {

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

                console.log(chat_list);
                // 会话列表
                chat_list.forEach( list => {
                    window.TS_WEB.dataBase.chatroom.where('[cid+owner]').equals([list.cid, window.TS_WEB.currentUserId ]).count( number => {
                        if(!number > 0) {
                            list.last_message_time = 0;
                            list.owner = window.TS_WEB.currentUserId;
                            // 将对话列表写入到本地数据库
                            window.TS_WEB.dataBase.chatroom.put(list);
                        }
                    });
                })

                // 获取授权
                var url = '/api/v1/im/users';
                $.ajax({
                    url: url,
                    type: 'GET',
                    success:function(res){
                        if (res.status) {
                            options.url = SOCKET_URL + '?token=' + res.data.im_password;
                            webSocket = $.websocket(options);
                        } else {
                            console.log('获取聊天授权失败');
                        }
                    }
                }, 'json');

                // 若有cid，创建对话信息
                if (cid != 0) message.listMessage(cid);
            }
        }
    },

    setRoom: function() {

    },

    listMessage: function(cid) {
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
                console.log(array);
                array = array.reverse();
                array.forEach( amessage => {
                    messageBody.user_id = amessage.uid;
                    messageBody.txt = amessage.txt;
                    messageBody.time = amessage.time;
                });
              }
            });
    },

    sendMessage: function() {

    }
}
