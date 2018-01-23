var easemob = {};
easemob = {
    // 初始化
    init: function(){
        easemob.conn = new WebIM.connection({
            isMultiLoginSessions: WebIM.config.isMultiLoginSessions,
            https: typeof WebIM.config.https === 'boolean' ? WebIM.config.https : location.protocol === 'https:',
            url: WebIM.config.xmppURL,
            heartBeatWait: WebIM.config.heartBeatWait,
            autoReconnectNumMax: WebIM.config.autoReconnectNumMax,
            autoReconnectInterval: WebIM.config.autoReconnectInterval,
            apiUrl: WebIM.config.apiURL,
            isAutoLogin: true
        });

        easemob.conn.listen({
            // 连接成功回调
            // 如果isAutoLogin设置为false，那么必须手动设置上线，否则无法收消息
            // 手动上线指的是调用conn.setPresence(); 如果conn初始化时已将isAutoLogin设置为true
            // 则无需调用conn.setPresence();        
            onOpened: function ( message ) {
            },
            //收到文本消息
            onTextMessage: function ( message ) {
                window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, window.TS.dataBase.room, () => {
                    // 因为返回的是来源用户ID是字符串类型，所以转换一下
                    message.from = parseInt(message.from);
                    // 查询会话是否存在
                    window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, message.from]).first(function(item){
                        var dbMsg = {};
                        dbMsg.id = message.id;
                        dbMsg.time = (new Date()).valueOf();
                        dbMsg.type = message.type;
                        dbMsg.mid = TS.MID;
                        dbMsg.uid = message.from;
                        dbMsg.touid = message.to;
                        dbMsg.read = 0;
                        dbMsg.txt = message.sourceMsg;

                        if (item === undefined) { // 不存在创建会话
                            var room = {
                                type: 'chat',
                                mid: TS.MID,
                                uid: message.from,
                                last_message_time: dbMsg.time,
                                last_message_txt: message.sourceMsg, 
                                del: 0
                            };
                            window.TS.dataBase.room.add(room).then(function(i){
                                // 插入聊天信息
                                dbMsg.cid = i;
                                window.TS.dataBase.message.add(dbMsg);

                                // 获取用户信息，创建会话
                                var user = getUserInfo(message.from);
                                var _user = _.keyBy([user], 'id');
                                easemob.users = Object.assign({}, easemob.users, _user);
                                room.id = i;
                                easemob.setNewCon(room);


                            });
                        } else { // 存在修改会话内容
                            window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, message.from]).modify({
                                last_message_time: dbMsg.time,
                                last_message_txt: message.sourceMsg
                            })

                            if (item.del == 1) {
                                window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, message.from]).modify({
                                    del: 0
                                });
                                // 设置会话
                                easemob.setNewCon(item);
                            }
                            dbMsg.cid = item.id;
                            dbMsg.read = (easemob.cid == dbMsg.cid && $('.chat_dialog').length > 0) ? 1 : 0;
                            window.TS.dataBase.message.add(dbMsg);

                        }

                        // 若聊天窗口为打开状态
                        if ($('.chat_dialog').length > 0) {
                            // 当前会话，添加消息
                            if (easemob.cid == dbMsg.cid && window.TS.MID == dbMsg.touid) {
                                easemob.setMes(dbMsg.txt, dbMsg.uid);
                            }
                            easemob.updateLastMes(dbMsg.cid, dbMsg.txt);
                        }
                    });
                });
            },
            // 失败回调
            onError: function ( message ) {
                console.log(message);
            },
        });

        easemob.database();

        // 获取IM账号密码
        if ($.cookie('im_passwd') === undefined) {
            axios.get('/api/v2/easemob/password/')
                .then(function (response) {
                    easemob.password = response.data.im_pwd_hash;
                    $.cookie('im_passwd', easemob.password, 1);
                    easemob.login();
                })
                .catch(function (error) {
                    showError(error.response.data);
                });
        } else {
            easemob.password = $.cookie('im_passwd');
            easemob.login();
        }

        // 获取用户信息并设置会话
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room
                .orderBy('last_message_time')
                .filter( (item) => {
                    return (item.mid == TS.MID);
                })
                .reverse()
                .toArray().then(function(items){
                    var uids = _.map(items, 'uid');
                    easemob.users = _.keyBy(getUserInfo(uids), 'id');
                    easemob.setOuterCon();
                });
        });

        // 设置未读消息定时器
        easemob.getUnreadMessage();
        var unread_message_timeout = window.setInterval(easemob.getUnreadMessage, 20000);
        easemob.getUnreadChats();
        var unread_chat_timeout = window.setInterval(easemob.getUnreadChats, 1000);
    },

    // IM登录
    login: function(){
        var options = { 
          apiUrl: WebIM.config.apiURL,
          user: TS.MID,
          pwd: easemob.password,
          appKey: WebIM.config.appkey
        };
        easemob.conn.open(options);
    },

    // 创建本地数据库
    database: function(){
        // 创建本地存储
        var db = new Dexie('TS_EASEMOB');
        db.version(1).stores({
            // message
            message: "id, time, cid, type, mid, uid, touid, txt, read, [cid+read]",

            // room
            room: "++id, type, mid, uid, last_message_time, last_message_txt, del, [mid+del], [mid+uid]",
        });

        window.TS.dataBase = db;

        // 添加IM小助手
        if (BOOT['im:helpers'] !== undefined) {
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
                window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, BOOT['im:helpers'][0]['uid']]).first(function(item){
                    if (item == undefined) {
                        var room = {
                            type: 'chat',
                            mid: TS.MID,
                            uid: BOOT['im:helpers'][0]['uid'],
                            last_message_time: (new Date()).valueOf(),
                            last_message_txt: '', 
                            del: 0
                        };
                        window.TS.dataBase.room.add(room);
                    }
                });
            });
        }
    },

    // 设置右侧会话
    setOuterCon: function(){
        var _this = this;
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where('[mid+del]').equals([TS.MID, 0]).each(function(item){
                var sidehtml = '<dd class="ms_chat" id="ms_chat_' + item.id + '" data-cid="' + item.id + '" data-name="' + _this.users[item.uid]['name'] + '"><a href="javascript:;" onclick="easemob.openChatDialog(0, '+ item.id +', '+ item.uid +')"><img src="' + getAvatar(_this.users[item.uid], 50) + '"/></a></dd>';

                $('#ms_fixed').append(sidehtml);
            });
        });
    },

    // 设置弹出会话
    setInnerCon: function(){
        var _this = this;
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where('[mid+del]').equals([TS.MID, 0]).each(function(item){
                if (item.del == 0 || item.id == easemob.cid) {
                    var css = item.id == easemob.cid ? 'class="room_item current_room"' : 'class="room_item"';

                    var last_message_txt = item.last_message_txt == undefined ? '' : item.last_message_txt;

                    var html = '<li ' + css + ' class="room_item" data-type="5" data-uid="' + item.uid + '" data-cid="' + item.id + '" id="chat_' + item.id + '">'
                                +      '<div class="chat_delete"><a href="javascript:;" onclick="easemob.delCon(' + item.id + ', ' + item.uid + ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
                                +      '<div class="chat_left_icon">'
                                +          '<img src="' + getAvatar(_this.users[item.uid]) + '" class="chat_svg">'
                                +       '</div>'
                                +      '<div class="chat_item">'
                                +          '<span class="chat_span">' + _this.users[item.uid]['name'] + '</span>'
                                +          '<div>' + last_message_txt + '</div>'
                                +      '</div>'
                                +  '</li>';

                    if (item.id == easemob.cid) {
                        $('#chat_pinneds').after(html);
                        easemob.listMes(easemob.cid, item.uid);
                    } else {
                        $('#root_list').append(html);
                    }
                }

                // 设置为未删除
                if (item.del == 1 && item.id == easemob.cid) {
                    window.TS.dataBase.room.where({id: item.id}).modify({
                        del: 0
                    })
                }
            });
        });
    },

    // 创建会话
    createCon: function(uid){
        checkLogin();
        var user = getUserInfo(uid);
        var _user = _.keyBy([user], 'id');
        easemob.users = Object.assign({}, easemob.users, _user);


        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, uid]).first(function(item){
                if (item === undefined) { // 不存在创建会话
                    var room = {
                        type: 'chat',
                        mid: TS.MID,
                        uid: uid,
                        last_message_time: (new Date()).valueOf(),
                        last_message_txt: '',
                        del: 0
                    };
                    window.TS.dataBase.room.add(room).then(function(i){
                        var sidehtml = '<dd class="ms_chat" id="ms_chat_' + i + '" data-cid="' + i + '" data-name="' + user['name'] + '"><a href="javascript:;" onclick="easemob.openChatDialog(0, '+ i +', '+ uid +')"><img src="' + getAvatar(user, 50) + '"/></a></dd>';
                        $('#ms_fixed').append(sidehtml);
                        easemob.cid = i;
                        easemob.openChatDialog(0, i, uid);
                    });
                } else { // 存在修改会话内容
                    if (item.del == 1) {
                        window.TS.dataBase.room.where('[mid+uid]').equals([TS.MID, uid]).modify({
                            del: 0
                        });
                    }
                    easemob.openChatDialog(0, item.id, uid);
                } 
            });
        });
    },

    // 删除会话
    delCon: function(cid, uid) {
        cancelBubble();
        var chat = $('#chat_' + cid);

        // 查找下个会话
        if (chat.next().length > 0) {
            var next_cid = chat.next().eq(0).data('cid');
            var next_uid = chat.next().eq(0).data('uid');
        } else if (chat.prev('.room_item').length > 0) {
            var next_cid = chat.prev().eq(0).data('cid');
            var next_uid = chat.next().eq(0).data('uid');   
        } else {
            var next_cid = 0;
        }

        $('#ms_chat_' + cid).remove();
        if ($('.chat_dialog').length > 0) chat.remove();

        // 清空会话，或者展示下个会话的聊天列表
        if (next_cid == 0) {
            easemob.cid = 0;
            $('#message_wrap').show();
            $('#chat_wrap').hide();
            messageData(3);
        } else {
            $('#chat_' + cid).addClass('current_room');
            easemob.listMes(next_cid, next_uid);
        }

        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, () => {
            window.TS.dataBase.room.where({id: cid}).modify({
                del: 1
            });
        });
    },

    // 设置新会话
    setNewCon: function(room){
        var _this = this;
        var sidehtml = '<dd class="ms_chat" id="ms_chat_' + room.id + '" data-cid="' + room.id + '" data-name="' + _this.users[room.uid]['name'] + '"><a href="javascript:;" onclick="easemob.openChatDialog(0, '+ room.id +', '+ room.uid +')"><img src="' + getAvatar(_this.users[room.uid], 50) + '"/></a></dd>';

        $('#ms_pinneds').after(sidehtml);

        if ($('.chat_dialog').length > 0) {
            var last_message_txt = room.last_message_txt == undefined ? '' : room.last_message_txt;

            var html = '<li class="room_item" data-type="5" data-cid="' + room['id'] + '" id="chat_' + room['id'] + '">'
                        +      '<div class="chat_delete"><a href="javascript:;" onclick="easemob.delCon(' + room['id'] + ', ' + room.uid + ')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-delbtn1"></use></svg></a></div>'
                        +      '<div class="chat_left_icon">'
                        +          '<img src="' + getAvatar(_this.users[room.uid]) + '" class="chat_svg">'
                        +       '</div>'
                        +      '<div class="chat_item">'
                        +          '<span class="chat_span">' + _this.users[room.uid]['name'] + '</span>'
                        +          '<div>' + last_message_txt + '</div>'
                        +      '</div>'
                        +  '</li>';
            $('#chat_pinneds').after(html);
        }
    },

    // 列出消息
    listMes: function(cid, uid){
        var _this = this;
        // 设置房间名
        $('#chat_wrap .body_title').html(_this.users[uid]['name']).show();
        $('#chat_cont').html('');

        // 查询消息
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
            window.TS.dataBase.message
                .orderBy('time')
                .filter( (item) => {
                    return (item.cid === cid);
                })
                .limit(15)
                .reverse()
                .toArray( array => {
                    var messageList = [];
                    var messageBody = {};
                    if(array.length) {
                        array = array.reverse();
                        array.forEach((value) => {
                            easemob.setMes(value.txt, value.uid);
                        });
                    }
                });
        });
    },

    // 发送消息
    sendMes: function(cid, touid) {
        var txt = $('#chat_text').val();
        if (txt == '') {
            $('#chat_text').focus();
            return false;
        }
        
        // 生成本地消息id
        var id = easemob.conn.getUniqueId();
        // 创建文本消息
        var msg = new WebIM.message('txt', id);
        msg.set({
            msg: txt,
            to: touid,
            roomType: false,
            success: function (id, serverMsgId) {
                // 消息入库
                var dbMsg = {};
                dbMsg.id = id;
                dbMsg.time = (new Date()).valueOf();
                dbMsg.cid = cid;
                dbMsg.type = 'chat';
                dbMsg.mid = dbMsg.uid = TS.MID;
                dbMsg.touid = touid;
                dbMsg.read = 1;
                dbMsg.txt = txt;
                window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
                    window.TS.dataBase.message.add(dbMsg);
                });
                easemob.setMes(txt, window.TS.MID);
            },
            fail: function(e){
                console.log(e);
            }
        });
        msg.body.chatType = 'singleChat';
        easemob.conn.send(msg.body);
    },

    // 设置消息
    setMes: function(txt, user_id){
        $('#chat_text').val('');
        txt = txt.replace(/\r\n/g, "<br>");
        txt = txt.replace(/\n/g, "<br>");

        if (user_id != window.TS.MID) {
            html = '<div class="chatC_left">'
                 +      '<img src="' + getAvatar(this.users[user_id]) + '" class="chat_avatar">'
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

    // 更新最后一条消息
    updateLastMes: function(cid, txt) {
        $('#chat_' + cid + ' .chat_item').find('div').html(txt);
    },

    // 获取未读消息数量html
    formatUnreadHtml: function(type, value) {
        if (type == 0) {
            var html = '<div class="unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        } else {
            var html = '<div class="chat_unread_div"><span>' + (value > 99 ? 99 : value) + '</span></div>';
        }
        return html;
    },

    // 设置未读消息数量
    setUnreadMes: function() {
        for (var i in TS.UNREAD) {
            if (TS.UNREAD[i] > 0) {
                $('#ms_' + i + ' .unread_div').remove();
                $('#ms_' + i).prepend(easemob.formatUnreadHtml(0, TS.UNREAD[i]));
                if ($('.chat_dialog').length > 0) {
                    $('#chat_' + i + ' .chat_unread_div').remove();
                    $('#chat_' + i).prepend(easemob.formatUnreadHtml(1, TS.UNREAD[i]));
                }
            } else {
                $('#ms_' + i + ' .unread_div').remove();
                $('#chat_' + i + ' .chat_unread_div').remove();
            }

            if ((i == 'comments' || i == 'likes') && TS.UNREAD['last_' + i]) {
                var txt = i == 'comments' ? '评论了你' : '点赞了你';
                $('#chat_' + i).find('.last_content').html(TS.UNREAD['last_' + i] + txt);
            }
        }
    },

    // 设置未读聊天消息数量
    setUnreadChat: function(cid, value) {
        $('#ms_chat_' + cid + ' .unread_div').remove();
        $('#ms_chat_' + cid).prepend(easemob.formatUnreadHtml(0, value));
        if ($('.chat_dialog').length > 0) {
            $('#chat_' + cid + ' .chat_unread_div').remove();
            $('#chat_' + cid).prepend(easemob.formatUnreadHtml(1, value));
        }
    },

    // 设置消息已读
    setRead: function(type, cid) {
        if (type == 0) { // 消息
            TS.UNREAD[cid] = 0;
            $('#ms_' + cid).find('.unread_div').remove();
            $('#chat_' + cid).find('.chat_unread_div').remove();
        } else { // 聊天
            $('#ms_chat_' + cid).find('.unread_div').remove();
            $('#chat_' + cid).find('.chat_unread_div').remove();
            window.TS.dataBase.transaction('rw?', window.TS.dataBase.message, () => {
                window.TS.dataBase.message.where({cid: cid}).modify({
                    read: 1
                });
            });
        }
    },

    // 打开消息对话框
    openChatDialog: function(type, cid, uid) {
        if (type == 0) { // 聊天消息
            easemob.setRead(1, cid);
            ly.load('/message/' + type + '/' + cid + '/' + uid, '', '720px', '572px');
        } else {
            ly.load('/message/' + type, '', '720px', '572px');
        }
    },

    // 获取未读消息数量
    getUnreadMessage: function() {
        // 获取未读通知数量
        axios.get('/api/v2/user/notifications')
          .then(function (response) {
                TS.UNREAD.notifications = response.headers['unread-notification-limit'];

                easemob.setUnreadMes();
          })
          .catch(function (error) {
            showError(error.response.data);
          });

        // 获取未读点赞，评论，审核通知数量
        axios.get('/api/v2/user/unread-count')
          .then(function (response) {
                var res = response.data;
                res.counts = res.counts ? res.counts : {};
                TS.UNREAD.comments = res.counts.unread_comments_count ? res.counts.unread_comments_count : 0;
                TS.UNREAD.last_comments = res.comments.length > 0 ? res.comments[0]['user']['name'] : '';
                TS.UNREAD.likes = res.counts.unread_likes_count ? res.counts.unread_likes_count : 0;
                TS.UNREAD.last_likes = res.likes.length > 0 ? res.likes[0]['user']['name'] : '';

                // 审核通知数量
                var pinneds_count = 0;
                for(var i in res.pinneds){
                    pinneds_count += res.pinneds[i]['count'];
                }
                TS.UNREAD.pinneds = pinneds_count;

                easemob.setUnreadMes();
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    },

    // 获取未读聊天消息数量
    getUnreadChats: function() {
        // 获取未读消息数量
        window.TS.dataBase.transaction('rw?', window.TS.dataBase.room, window.TS.dataBase.message, () => {
            window.TS.dataBase.room.where({mid: TS.MID}).each( value => {
                window.TS.dataBase.message.where('[cid+read]').equals([value.id, 0]).count( number => {
                    if (number > 0) {
                        easemob.setUnreadChat(value.id, number);
                    }
                });
            });
        })
        .catch(e => {
            console.log(e);
        });
    }
}