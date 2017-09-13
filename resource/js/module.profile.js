
/**
 * 赞核心Js
 * @type {Object}
 */
var digg = {
    // 给工厂调用的接口
    _init: function(attrs) {
        digg.init();
    },
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(feed_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/like';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + feed_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg(' + feed_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                    } else {
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>' + num + '</font></a>');
                    }
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });

    },
    delDigg: function(feed_id, page) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/unlike';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + feed_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                    } else {
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>' + num + '</font></a>');
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
        $('.J-comment-feed' + obj.row_id).attr('to_uid', obj.to_uid);
        var _textarea = $('#editor_box' + obj.row_id).find('textarea');
        if (_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
        var html = '回复@' + obj.to_uname + ' ：';
        //清空输入框
        _textarea.val('');
        _textarea.val(html);
        _textarea.focus();
    },

    // 列表发表评论
    addComment: function(afterComment, obj) {
        var to_uid = $(obj).attr('to_uid') || 0;
        var feedid = $(obj).attr('row_id') || 0;
        var _textarea = $('#editor_box' + feedid).find('textarea');

        if (_textarea.size() == 0) {
            _textarea = $(obj).parent().find('input:eq(0)');
        }
        _textarea = _textarea.get(0);
        var strlen = getLength(_textarea.value);
        var leftnums = initNums - strlen;
        if (leftnums < 0 || leftnums == initNums) {
            noticebox('评论内容长度为1-' + initNums + '字', 0);
            return false;
        }
        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }
        var formData = {
            body: _textarea.value,
        };
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = request_url.feed_comment.replace('{feed_id}', feedid);

        obj.innerHTML = '评论中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    if (obj != undefined) {
                        obj.innerHTML = '评论';
                    }
                    var html = '<p class="comment' + res.comment.id + ' comment_con">';
                    html += '<span>' + NAME + '：</span>' + formData.body + '';
                    html += '<a class="fs-14 del_comment" onclick="comment.delComment(' + res.comment.id + ', ' + feedid + ');">删除</a>';
                    html += '</p>';
                    var commentBox = $('.comment_box' + feedid);
                    var commentNum = $('.cs' + feedid);
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                        commentNum.text(parseInt(commentNum.text()) + 1);
                    }
                } else {
                    noticebox(res.message, 0);
                }
            }
        });
    },
    // 文章列表评论
    addNewComment: function(afterComment, obj) {
        var to_uid = $(obj).attr('to_uid') || 0;
        var news_id = $(obj).attr('row_id') || 0;
        var _textarea = $('#editor_box' + news_id).find('textarea');

        if (_textarea.size() == 0) {
            _textarea = $(obj).parent().find('input:eq(0)');
        }
        _textarea = _textarea.get(0);
        var strlen = getLength(_textarea.value);
        var leftnums = initNums - strlen;
        if (leftnums < 0 || leftnums == initNums) {
            noticebox('评论内容长度为1-' + initNums + '字', 0);
            return false;
        }
        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }
        var formData = {
            body: _textarea.value,
        };
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = request_url.comment_news.replace('{news_id}', news_id);
        obj.innerHTML = '评论中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    if (obj != undefined) {
                        obj.innerHTML = '评论';
                    }
                    var html = '<p><span>' + NAME + '：</span>' + formData.body + '</p>';
                    var html = '<p class="comment' + res.id + ' comment_con">';
                    html += '<span>' + NAME + '：</span>' + formData.body + '';
                    html += '<a class="fs-14 del_comment" onclick="comment.delNewsComment(' + res.id + ', ' + news_id + ');">删除</a>';
                    html += '</p>';
                    var commentBox = $('.comment_box' + news_id);
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                    }
                } else {
                    noticebox(res.message, 0);
                }
            }
        });
    },
    delComment: function(comment_id, feed_id) {
        var url = request_url.del_feed_comment.replace('{feed_id}', feed_id);
        url = url.replace('{comment_id}', comment_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) { noticebox('删除失败请重试', 0); },
            success: function(res) {
                $('.comment' + comment_id).fadeOut(1000);
                var commentNum = $('.comment_count').text();
                $('.comment_count').text(parseInt(commentNum) - 1);
                var nums = $('.cs' + feed_id);
                nums.text(parseInt(nums.text()) - 1);
            }
        });
    },
    delNewsComment: function(comment_id, news_id) {
        var url = request_url.del_news_comment.replace('{news_id}', news_id);
        url = url.replace('{comment_id}', comment_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) { noticebox('删除失败请重试', 0); },
            success: function(res) {
                $('.comment' + comment_id).fadeOut(1000);
                var nums = $('.cs' + news_id);
                nums.text(parseInt(nums.text()) - 1);
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
    addCollect: function(feed_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = request_url.collect_feed.replace('{feed_id}', feed_id);

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
                    $collect = $('#collect' + feed_id);
                    var num = $collect.attr('rel');
                    num++;
                    $collect.attr('rel', num);
                    if (page == 'read') {
                        $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect(' + feed_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="collect_num">' + num + '</font>人收藏</a>');
                    } else {
                        $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect(' + feed_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏</a>');
                    }
                } else {
                    noticebox(res.message, 0);
                }

                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(feed_id, page) {

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = request_url.del_collect_feed.replace('{feed_id}', feed_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $collect = $('#collect' + feed_id);
                    var num = $collect.attr('rel');
                    num--;
                    $collect.attr('rel', num);
                    if (page == 'read') {
                        $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect(' + feed_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="collect_num">' + num + '</font>人收藏</a>');
                    } else {
                        $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏</a>');
                    }
                } else {
                    noticebox(res.message, 0);
                }

                collect.collectlock = 0;
            }
        });
    },
    addNewsCollect: function(news_id) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/news/'+news_id+'/collections';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $collect = $('#collect' + news_id);
                    var num = $collect.attr('rel');
                    num++;
                    $collect.attr('rel', num);
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.delNewsCollect(' + news_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cos">' + num + '</font></a>');
                } else {
                    noticebox(res.message, 0);
                }

                collect.collectlock = 0;
            }
        });

    },
    delNewsCollect: function(news_id) {

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = '/api/v2/news/'+news_id+'/collections';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $collect = $('#collect' + news_id);
                    var num = $collect.attr('rel');
                    num--;
                    $collect.attr('rel', num);
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.addNewsCollect(' + news_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cos">' + num + '</font></a>');
                } else {
                    noticebox(res.message, 0);
                }

                collect.collectlock = 0;
            }
        });
    }
};



$(function() {

    $('.change_cover').on('click', function() {
        $('#cover').click();
    })

    $('#cover').on('change', function(e) {
        var file = e.target.files[0];
        var formDatas = new FormData();
            formDatas.append("image", file);
            $.ajax({
                url: '/api/v2/user/bg',
                type: 'POST',
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(res) {
                    noticebox('更换背景图成功', 1);
                    $('.user_bg').attr('src', window.URL.createObjectURL(file));
                }
            });
    });
    // 显示回复框
    $('#feeds_list, #article-list, #content-list').on('click', '.J-comment-show', function() {
        checkLogin();
        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });

    // 回复初始化
    $('#feeds-list, #content-list').on('click', '.J-reply-comment', function() {
        var attrs = urlToObject($(this).data('args'));
        comment.initReply(attrs);
    });

})