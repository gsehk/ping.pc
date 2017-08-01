﻿var news = {};

news.setting = {};

/**
 * 文章初始化
 * $param object option 文章配置相关数据
 * @return void
 */
news.init = function(option) {
    this.setting.container = option.container; // 容器ID
    this.setting.loadcount = option.loadcount || 0; // 加载次数
    this.setting.loadmax = option.loadmax || 40; // 加载最大次数
    this.setting.after = option.after || 0; // 最大文章ID
    this.setting.cid = option.cid || 0; //  文章分类ID
    this.setting.canload = option.canload || true; // 是否能加载

    news.bindScroll();

    if ($(news.setting.container).length > 0 && this.setting.canload) {
        $(news.setting.container + ' .loading').remove();
        $(news.setting.container).append(loadHtml);
        news.loadMore();
    }
};
/**
 * 页面底部触发事件
 * @return void
 */
news.bindScroll = function() {
    // 底部触发事件绑定
    $(window).bind('scroll resize', function() {
        // 加载指定次数后，将不能自动加载
        if (news.isLoading()) {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                if ($(news.setting.container).length > 0) {
                    $(news.setting.container).append(loadHtml);
                    news.loadMore();
                }
            }
        }
    });
};

/**
 * 判断是否文章时候能自动加载
 * @return boolean 文章是否能自动加载
 */
news.isLoading = function() {
    var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
    return status;
};

/**
 * 获取加载的数据信息
 * @return void
 */
news.loadMore = function() {
    // 将能加载参数关闭
    news.setting.canload = false;
    news.setting.loadcount++;
    // 异步提交，获取相关频道数据
    var postArgs = {};
    postArgs.after = news.setting.after;
    postArgs.cate_id = news.setting.cid;
    $.get('/news/lists', postArgs, function(res) {
        if (res.data.length > 0) {
            news.setting.canload = true;
            // 修改加载ID
            news.setting.after = res.data[res.data.length - 1].id;
            var html = '';
            var data = res.data;
            for (var i in data) {
                html += '<div class="inf_list">' +
                    '<div class="inf_img">' +
                    '<a href="/news/read/' + data[i].id + '">' + '<img class="lazy" width="230" height="163" data-original="' + request_url.images + data[i].storage + '?w=230&h=163" />' + '</a>' +
                    '</div>' +
                    '<div class="inf_word">' +
                    '<a href="/news/read/' + data[i].id + '">' +
                    '<div class="infW_title">' + data[i].title + '</div>' +
                    '</a>' +
                    '<p>' + data[i].subject + '</p>' +
                    '<div class="inf_bm">' +
                    '<span class="inf_time">' + data[i].created_at + '</span>' +
                    '<span class="inf_comment">' + data[i].comment_count + '评论<span>|</span>' + data[i].collection_count + '收藏</span>' +
                    '</div>' +
                    '</div>' +
                    '</div> ';
            }
            if (news.setting.loadcount == 1) {
                $(news.setting.container).html(html);
            } else {
                $(news.setting.container).append(html);
                $(news.setting.container + ' .loading').remove();
            }
            $("img.lazy").lazyload({ effect: "fadeIn" });
        } else {
            news.setting.canload = false;
            if (news.setting.loadcount == 1) {
                no_data(news.setting.container, 1, ' 暂无相关内容');
                $(news.setting.container + ' .loading').html('');
            } else {
                $(news.setting.container + ' .loading').html('暂无相关内容');
            }
        }
    });
};

/* 加载推荐资讯 */
var recommend = {
    opt: {},
    init: function(option) {
        this.opt.container = option.container;
        this.opt.limit = option.loadlimit || 6;
        this.opt.cate_id = option.cate_id || 0;
        this.opt.canload = option.canload || true;

        if ($(recommend.opt.container).length > 0 && this.opt.canload) {
            $(recommend.opt.container).append(loadHtml);
            recommend.loadMore();
        }
    },
    loadMore: function() {
        recommend.opt.canload = false;
        var postArgs = {};
        postArgs.limit = recommend.opt.limit;
        postArgs.cate_id = recommend.opt.cate_id;
        $.ajax({
            url: request_url.get_news,
            type: 'GET',
            data: postArgs,
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.data.length > 0) {
                    recommend.opt.canload = true;
                    var data = res.data,
                        html = '';
                    for (var i in data) {
                        html += '<span>' + data[i].title + '</span>';
                    }
                    $(recommend.opt.container).append(html);
                    $(news.setting.container + ' .loading').remove();
                } else {
                    recommend.opt.canload = false;
                    $(news.setting.container + ' .loading').html('暂无相关内容');
                }
            }
        });
    },
};

/**
 * 文章投稿。
 */
$('.subject-submit').on('click', function() {
    var args = {
        'author': $('#subject-author').val(),
        'title': $('#subject-title').val(),
        'subject': $('#subject-abstract').val(),
        'content': $(editor).html(),
        'image': $('#subject-image').val(),
        'from': $('#subject-from').val(),
        'cate_id': $('#cate_ids').val(),
        'news_id': $('#news_id').val() || 0,
    };
    if (!args.title || getLength(args.title) > 20) {
        noticebox('文章标题不合法', 0);
        return false;
    }
    if (!$(editor).text() || getLength($(editor).text()) > 5000) {
        noticebox('文章内容不合法', 0);
        return false;
    }
    if (!args.image) {
        noticebox('请上传封面图片', 0);
        return false;
    }
    var url = request_url.contribute.replace('{category}', args.cate_id);
    $.ajax({
        url: url,
        type: 'POST',
        data: args,
        dataType: 'json',
        error: function(xml) {},
        success: function(res, data, xml) {
            if (xml.status == 201) {
                noticebox(res.message, 1, '/profile/article/'+ MID);
            } else {
                noticebox(res.message, 0);
            }
        }
    });
});

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
    addDigg: function(news_id) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = request_url.digg_news.replace('{news_id}', news_id);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
                    $digg = $('#digg' + news_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    $('#digg' + news_id).html('<a href="javascript:;" onclick="digg.delDigg(' + news_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });

    },
    delDigg: function(news_id) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = request_url.digg_news.replace('{news_id}', news_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + news_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    $('#digg' + news_id).html('<a href="javascript:;" onclick="digg.addDigg(' + news_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });
    }
};

/**
 * 文章收藏
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
    addCollect: function(news_id) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = request_url.collect_news.replace('{news_id}', news_id);

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            // beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', TOKEN); },
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
                    $collect = $('#collect' + news_id);
                    var num = $collect.attr('rel');
                    num++;
                    $collect.attr('rel', num);
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.delCollect(' + news_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">' + num + '</font>收藏</a>');
                } else {
                    alert(res.message);
                }

                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(news_id) {

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = request_url.collect_news.replace('{news_id}', news_id);
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
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.addCollect(' + news_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">' + num + '</font>收藏</a>');
                } else {
                    alert(res.message);
                }

                collect.collectlock = 0;
            }
        });
    }
};

/**
 * 核心评论对象
 */
var comment = {
    // 给工厂调用的接口
    _init: function(attrs) {
        comment.init(attrs);
    },
    // 初始化评论对象
    init: function(attrs) {
        this.row_id = attrs.row_id || 0;
        this.after = attrs.after || 0;
        this.limit = attrs.limit || 5;
        this.to_uid = attrs.to_uid || 0;
        this.canload = attrs.canload || 1;
        this.reply_to_user_id = attrs.reply_to_user_id || 0;
        this.box = attrs.box || '#comment_detail';
        this.editor = attrs.editor || '#mini_editor';

        this.bindScroll();

        if ($(this.box).length > 0 && this.canload > 0) {
            $(this.box).append(loadHtml);
            comment.loadMore();
        }
    },
    // 页面底部触发事件
    bindScroll: function() {
        // 底部触发事件绑定
        $(window).bind('scroll resize', function() {
            if (comment.canload == true) {
                var scrollTop = $(this).scrollTop();
                var scrollHeight = $(document).height();
                var windowHeight = $(this).height();
                if (scrollTop + windowHeight == scrollHeight) {
                    if ($(comment.box).length > 0) {
                        $(comment.box).append(loadHtml);
                        comment.loadMore();
                    }
                }
            }
        });
    },
    // 显示回复块
    loadMore: function() {
        comment.canload = false;
        var url = request_url.get_comment.replace('{news_id}', comment.row_id);
        $.ajax({
            url: url,
            type: 'GET',
            data: { after: comment.after},
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.data.length > 0) {
                    comment.canload = true;
                    comment.after = res.after;
                    var data = res.data,
                        html = '';
                    for (var i in data) {
                        html += '<div class="delComment_list comment' + data[i].id + '">';
                        html += '<div class="comment_left">';
                        html += '<a href="/profile/' + data[i].user_id + '"><img src="' + data[i].user.avatar + '" class="c_leftImg" /></a>';
                        html += '</div>';
                        html += '<div class="comment_right">';
                        html += '<a href="/profile/' + data[i].user_id + '"><span class="del_ellen">' + data[i].user.name + '</span></a>';
                        html += '<span class="c_time">' + data[i].created_at + '</span>';
                        html += '<p class="comment_con">' + data[i].body + '';
                        html += '<span class="del_huifu">';
                        if (data[i].user_id != MID) {
                            html += '<a href="javascript:void(0)" data-args="editor=#mini_editor&box=#comment_detail&to_comment_uname=' + data[i].user.name + '&canload=0&to_uid=' + data[i].user_id + '"';
                            html += 'class="J-reply-comment">回复</a>';
                        }
                        if (data[i].user_id == MID) {
                            html += '<a href="javascript:void(0)" onclick="comment.delComment(' + data[i].id + ', ' + data[i].commentable_id + ')"';
                            html += 'class="del_comment">删除</a>';
                        }
                        html += '</span>';
                        html += '</p></div></div>';
                    }
                    $(comment.box).append(html);
                    $('.del_left .loading').remove();
                    $('.J-reply-comment').on('click', function() {
                        var attrs = urlToObject($(this).data('args'));
                        comment.initReply(attrs);
                    });
                } else {
                    comment.canload = false;
                    $('.del_left .loading').html('暂无相关内容');
                }
            }
        });
    },
    // 初始化回复操作
    initReply: function(obj) {
        $('#J-comment-news').attr('to_uid', obj.to_uid);
        var _textarea = $(obj.editor);
        if (_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
        var html = '回复@' + obj.to_comment_uname + ' ：';
        //清空输入框
        _textarea.val('');
        _textarea.val(html);
        _textarea.focus();
    },
    // 发表评论
    addComment: function(afterComment, obj) {
        var box = this.box;
        var _textarea = $('#mini_editor');
        if (_textarea.size() == 0) {
            return;
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
        if (this.to_uid > 0) {
            formData.reply_user = this.to_uid;
        }         
        var url = request_url.comment_news.replace('{news_id}', this.row_id);
        var _this = this;
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
                    var html = '<div class="delComment_list comment' + res.comment.id + '">';
                    html += '<div class="comment_left">';
                    html += '<a href="/profile/' + MID + '"><img src="' + AVATAR + '" class="c_leftImg" /></a>';
                    html += '</div>';
                    html += '<div class="comment_right">';
                    html += '<a href="/profile/' + MID + '"><span class="del_ellen">' + NAME + '</span></a>';
                    html += '<span class="c_time">刚刚</span>';
                    html += '<p class="comment_con">' + formData.body + '';
                    html += '<a href="javascript:void(0)" onclick="comment.delComment(' + res.comment.id + ', ' + comment.row_id + ')"';
                    html += 'class="del_comment">删除</a>';
                    html += '</p></div></div>';
                    var commentBox = $(comment.box);
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
                    $('.comment_count').text(parseInt(commentNum) + 1);
                } else {
                    noticebox(res.message, 0);
                }
            }
        });
    },
    delComment: function(comment_id, news_id) {
        var url = request_url.del_news_comment.replace('{news_id}', news_id);
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
            }
        });
    }
};

$(document).ready(function() {
    // 近期热点切换
    $('#j-recent-hot a').hover(function() {
        $('.list').hide();
        $('.list' + $(this).attr('cid')).show();
        $('#j-recent-hot a').removeClass('a_border');
        $(this).addClass('a_border');
    });
});
