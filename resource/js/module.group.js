// var group={};

var post = {};


post.createPost = function (group_id) {
    if (MID == 0) {
        window.location.href = '/passport/login';
        return false;
    }

    var images = [];
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });

    var data = {
        title: '发一个圈子动态呢',
        content: $('#feed_content').val(),
        // images: images,
        group_post_mark: MID + new Date().getTime(),
    };
    var strlen = getLength(data.content);
    var leftnums = initNums - strlen;
    if (leftnums < 0 || leftnums == initNums) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }
    console.log(data)
    $.ajax({
        url: '/api/v2/groups/' + group_id + '/posts',
        type: 'post',
        data: data,
        success: function(res) {
            noticebox('发布成功', 1);
            $('.feed_picture').html('').hide();
            $('#feed_content').val('');
            post.afterCreatePost(group_id, res.id);
        },
        error: function(xhr){
            showError(xhr.responseJSON);
        }
    })
};

post.afterCreatePost = function (group_id, post_id) {
    var url = '/group/getPost';
    $.ajax({
        url: url,
        type: 'get',
        data: {
            group_id: group_id,
            post_id: post_id
        },
        dataType: 'json',
        success: function(res) {
            if ($('#feeds_list').find('.no_data_div').length > 0) {
                $('#feeds_list').find('.no_data_div').remove();
            }
            $(res.data).hide().prependTo('#feeds_list').fadeIn('slow');
            $("img.lazy").lazyload({effect: "fadeIn"});
        }
    })
};

post.delPost = function(group_id, post_id) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url ='/api/v2/groups/' + group_id + '/posts/' + post_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#feed' + post_id).fadeOut();
                layer.closeAll();
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    });
};

/**
 * 赞核心Js
 * @type {Object}
 */
var digg={
    // 给工厂调用的接口
    _init: function(attrs) {
        digg.init();
    },
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(group_id, post_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;


        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/like';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + post_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.delDigg(' + post_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                    } else {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.delDigg(' + post_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>' + num + '</font></a>');
                    }
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });

    },
    delDigg: function(group_id, post_id, page) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/like';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + post_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.addDigg(' + post_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                    } else {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.addDigg(' + post_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>' + num + '</font></a>');
                    }
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });
    }
};

/**
 * 核心评论对象
 */
var comment = {
    // 初始化回复操作
    initReply: function(obj) {
        console.log(obj);
        $('.J-comment-feed' + obj.row_id).attr('to_uid', obj.to_uid);
        var _textarea = $('#editor_box' + obj.row_id).find('textarea');
        if (_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
        var html = '回复@' + obj.to_uname + ' ：';
        //清空输入框
        _textarea.val('');
        _textarea.val(html);
        _textarea.focus();
    },
    // 初始化回复操作
    initReadReply: function(obj) {
        $('#J-comment-news').attr('to_uid', obj.to_uid);
        var _textarea = $(obj.editor);
        if (_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
        var html = '回复@' + obj.to_comment_uname + ' ：';
        //清空输入框
        _textarea.val('');
        _textarea.val(html);
        _textarea.focus();
    },

    // 列表发表评论
    addComment: function(afterComment, obj) {
        var to_uid = $(obj).attr('to_uid') || 0;
        var group_id = $(obj).attr('group_id') || 0;
        var post_id = $(obj).attr('row_id') || 0;
        var _textarea = $('#editor_box' + post_id).find('textarea');

        if (_textarea.size() == 0) {
            _textarea = $(obj).parent().find('input:eq(0)');
        }
        _textarea = _textarea.get(0);

        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }
        var formData = {
            body: _textarea.value,
        };
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/comments';
        obj.innerHTML = '评论中..';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (obj != undefined) {
                    obj.innerHTML = '评论';
                }
                var html = '<p class="comment'+res.comment.id+' comment_con">';
                html += '<span>' + NAME + '：</span>' + formData.body + '';
                html += '<a class="del_comment" onclick="comment.delComment('+res.comment.id+', '+post_id+');">删除</a>';
                html += '</p>';
                var commentBox = $('#comment_ps' + post_id);
                var commentNum = $('.cs' + post_id);
                if ("undefined" != typeof(commentBox)) {
                    commentBox.prepend(html);
                    _textarea.value = '';
                    $('.nums').text(initNums);
                    commentNum.text(parseInt(commentNum.text())+1);
                }
            },
            error: function(xhr){
                showError(xhr.responseJSON);
                obj.innerHTML = '评论';
            }
        });
    },
    // 详情发表评论
    addReadComment: function(attrs, obj) {
        var _textarea = $('#mini_editor');
        if (_textarea.size() == 0) {
            return;
        }
        _textarea = _textarea.get(0);
        var formData = {
            body: _textarea.value,
        };
        if (attrs.to_uid > 0) {
            formData.reply_user = attrs.to_uid;
        }
        if ("undefined" != typeof(this.addReadComment) && (this.addReadComment == true)) {
            return false; //不要重复评论
        }
        var url = '/api/v2/groups/' + attrs.group_id + '/posts/' + attrs.row_id + '/comments';
        var _this = this;
        obj.innerHTML = '评论中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res, data, xml) {
                if (obj != undefined) {
                    obj.innerHTML = '评论';
                }
                var html  = '<div class="comment_item" id="comment_item_'+res.comment.id+'">';
                html += '    <dl class="clearfix">';
                html += '        <dt>';
                html += '            <img src="'+AVATAR+'" width="50">';
                html += '        </dt>';
                html += '        <dd>';
                html += '            <span class="reply_name">'+NAME+'</span>';
                html += '            <div class="reply_tool">';
                html += '                <span class="reply_time">刚刚</span>';
                html += '                <span class="reply_action"><i class="icon iconfont icon-gengduo-copy"></i></span>';
                html += '            </div>';
                html += '            <div class="replay_body">'+formData.body+'</div>';
                html += '        </dd>';
                html += '    </dl>';
                html += '</div>';
                var commentBox = $('#comment_box');
                if ("undefined" != typeof(commentBox)) {
                    commentBox.prepend(html);
                    _textarea.value = '';
                    $('.nums').text(initNums);
                    /*绑定回复操作*/
                    $('.J-reply-comment').on('click', function() {
                        comment.initReply();
                    });
                }
                var commentNum = $('.comment_count').text();
                $('.comment_count').text(parseInt(commentNum)+1);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
                obj.innerHTML = '评论';
            }
        });
    },
    delComment: function(comment_id, post_id) {
        // 获取group_id
        var urlString = window.location.pathname;
        var urlArray = urlString.split("/");
        var group_id = urlArray[2];

        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/comments/' + comment_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#comment_item_'+comment_id).fadeOut();
                var commentNum = $('.comment_count').text();
                $('.comment_count').text(parseInt(commentNum)-1);
                var nums = $('.cs' + post_id);
                nums.text(parseInt(nums.text())-1);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    }
};

/**
 * 收藏核心Js
 * @type {Object}
 */
var collect = {
    // 给工厂调用的接口
    _init: function(attrs) {
        collect.init();
    },
    init: function() {
        collect.collectlock = 0;
    },
    addCollect: function(group_id, post_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/collection';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + post_id);
                var num = $collect.attr('rel');
                num++;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.delCollect(' + post_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">' + num + '</font>人收藏</a>');
                } else {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.delCollect(' + post_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏</a>');
                }

                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(group_id, post_id, page) {
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/collection';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + post_id);
                var num = $collect.attr('rel');
                num--;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.addCollect(' + post_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">' + num + '</font>人收藏</a>');
                } else {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.addCollect(' + post_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏</a>');
                }
                collect.collectlock = 0;
            }
        });
    }
};