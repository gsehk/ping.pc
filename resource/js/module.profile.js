var weibo = {};
weibo.setting = {};
/**
 * 微博初始化
 * $param object option 微博配置相关数据
 * @return void
 */
weibo.init = function(option) {
    this.setting.container = option.container; // 容器ID
    this.setting.loadcount = option.loadcount || 0; // 加载次数
    this.setting.loadmax = option.loadmax || 40; // 加载最大次数
    this.setting.after = option.after || 0; // 最大微博ID
    this.setting.user_id = option.user_id || 0; //  用户user_id
    this.setting.type = option.type || 'all'; //  微博分类
    this.setting.canload = option.canload || true; // 是否能加载
    this.setting.loading = option.loading || '.dy_cen'; //加载图位置
    this.setting.url = request_url.get_user_feed;
    this.setting.cate = 'users';
    weibo.bindScroll();
    if ($(weibo.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $(weibo.setting.loading).after(loadHtml);
        weibo.loadMore();
    }
};
/**
 * 页面底部触发事件
 * @return void
 */
weibo.bindScroll = function() {
    $(window).bind('scroll resize', function() {
        if (weibo.isLoading()) {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                if ($(weibo.setting.container).length > 0) {
                    $('.loading').remove();
                    $(weibo.setting.loading).after(loadHtml);
                    weibo.loadMore();
                }
            }
        }
    });
};

/**
 * 判断是否微博时候能自动加载
 * @return boolean 微博是否能自动加载
 */
weibo.isLoading = function() {
    var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
    return status;
};

/**
 * 获取加载的数据信息
 * @return void
 */
weibo.loadMore = function() {
    // 将能加载参数关闭
    weibo.setting.canload = false;
    weibo.setting.loadcount++;

    var postArgs = {};
    postArgs.after = weibo.setting.after;
    postArgs.cate = weibo.setting.type;
    postArgs.type = weibo.setting.cate;
    $.ajax({
        url: weibo.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error: function(xml) {},
        success: function(res) {
            if (res.after > 0) {
                weibo.setting.canload = true;
                // 修改加载ID
                weibo.setting.after = res.after;
                var html = res.data;
                if (weibo.setting.loadcount == 1) {
                    $(weibo.setting.container).html(html);
                    $('.loading').remove();
                } else {
                    $(weibo.setting.container).append(html);
                }
                $("img.lazy").lazyload({ effect: "fadeIn" });
            } else {
                weibo.setting.canload = false;
                if (weibo.setting.loadcount == 1) {
                    no_data(weibo.setting.container, 1, ' 暂无相关内容');
                    $('.loading').html('');
                } else {
                    $('.loading').html('暂无相关内容');
                }
            }
        }
    });
};
weibo.delFeed = function(feed_id) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url = request_url.delete_feed.replace('{feed_id}', feed_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function() {
                layer.msg(' 删除失败请稍后再试', { icon: 0 });
            },
            success: function(res) {
                $('#feed' + feed_id).fadeOut(1000);
                layer.closeAll();
            }
        });
    });
};
weibo.denounce = function(obj) {
    var feed_id = $(obj).attr('feed_id');
    var to_uid = $(obj).attr('to_uid');
    layer.prompt(function(val, index) {
        if (!val) {
            layer.msg(' 请填写举报理由', { icon: 0 });
        }
        var url = request_url.denounce_feed.replace('{feed_id}', feed_id);
        $.ajax({
            url: url,
            type: 'POST',
            data: { aid: feed_id, to_uid: to_uid, reason: val, from: 'weibo' },
            dataType: 'json',
            error: function() {
                layer.msg(' 举报失败请稍后再试', { icon: 0 });
            },
            success: function(res) {
                if (res.status == true) {
                    layer.msg(' 举报成功', { icon: 1 });
                } else {
                    layer.msg(res.message, { icon: 0 });
                }

            }
        });
        layer.close(index);
    });
};

var collection = {};
collection.setting = {};
/**
 * 收藏初始化
 * @return void
 */
collection.init = function(option) {
    this.setting.container = option.container; // 容器ID
    this.setting.loadcount = option.loadcount || 0; // 加载次数
    this.setting.loadmax = option.loadmax || 40; // 加载最大次数
    this.setting.after = option.after || 0; // 最后一条数据id
    this.setting.user_id = option.user_id || 0; //  用户user_id
    this.setting.type = option.type || 0; //   分类
    this.setting.canload = option.canload || true; // 是否能加载
    this.setting.loading = option.loading || '.dy_cen'; //加载图位置
    switch (option.type) {
        case 'feed':
            this.setting.url = request_url.get_feed_collect;
            break;
        case 'news':
            this.setting.url = request_url.get_news_collect;
            break;
        default:
            this.setting.url = request_url.get_user_news;
            break;
    }
    collection.bindScroll();
    if ($(collection.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $(collection.setting.loading).after(loadHtml);
        collection.loadMore();
    }
};
/**
 * 页面底部触发事件
 * @return void
 */
collection.bindScroll = function() {
    // 底部触发事件绑定
    $(window).bind('scroll resize', function() {

        // 加载指定次数后，将不能自动加载
        if (collection.isLoading()) {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                if ($(collection.setting.container).length > 0) {
                    $('.loading').remove();
                    $(collection.setting.loading).after(loadHtml);
                    collection.loadMore();
                }
            }
        }
    });
};
/**
 * 判断是否能自动加载
 * @return boolean
 */
collection.isLoading = function() {
    var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
    return status;
};

/**
 * 获取加载的数据信息
 * @return void
 */
collection.loadMore = function() {
    // 将能加载参数关闭
    collection.setting.canload = false;
    collection.setting.loadcount++;

    var postArgs = {};
    postArgs.after = collection.setting.after;
    postArgs.type = collection.setting.type;
    postArgs.user = collection.setting.user_id;
    $.ajax({
        url: collection.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error: function(xml) {},
        success: function(res) {
            if (res.after > 0) {
                collection.setting.canload = true;
                // 修改加载ID
                collection.setting.after = res.after;
                var html = res.data;
                if (collection.setting.loadcount == 1) {
                    $(collection.setting.container).html(html);
                    $('.loading').remove();
                } else {
                    $(collection.setting.container).append(html);
                }
                $("img.lazy").lazyload({ effect: "fadeIn" });
            } else {
                collection.setting.canload = false;
                if (collection.setting.loadcount == 1) {
                    no_data(collection.setting.container, 1, ' 暂无相关内容');
                    $('.loading').html('');
                } else {
                    $('.loading').html('暂无相关内容');
                }
            }
        }
    });
};

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
            window.location.href = '/passport/index';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = request_url.feed_like.replace('{feed_id}', feed_id);
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
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg(' + feed_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font>' + num + '</font>人喜欢</a>');
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

        var url = request_url.feed_unlike.replace('{feed_id}', feed_id);
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
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>' + num + '</font>人喜欢</a>');
                    } else {
                        $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>' + num + '</font></a>');
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
    // 初始化评论对象
    init: function(attrs) {
        this.row_id = attrs.row_id || 0;
        this.after = attrs.after || 0;
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
        var url = request_url.feed_commnets.replace('{feed_id}', comment.row_id);
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
                        html += '<div class="delComment_list">';
                        html += '<div class="comment_left">';
                        html += '<img src="' + data[i].user.avatar + '" class="c_leftImg" />';
                        html += '</div>';
                        html += '<div class="comment_right">';
                        html += '<span class="del_ellen">' + data[i].user.name + '</span>';
                        html += '<span class="c_time">' + data[i].created_at + '</span>';
                        html += '<i class="icon iconfont icon-gengduo-copy"></i>';
                        html += '<p class="comment_con">' + data[i].body + '';
                        html += '<span class="del_huifu">';
                            if (data[i].user_id != MID) {
                                html += '<a href="javascript:void(0)" data-args="editor=#mini_editor&box=#comment_detail&to_comment_uname=' + data[i].user.name + '&canload=0&to_uid=' + data[i].user_id + '"';
                                html += 'class="J-reply-comment">回复</a>';
                            }
                            if (data[i].user_id == MID) {
                                html += '<a href="javascript:void(0)" onclick="comment.delComment('+data[i].id+', '+data[i].commentable_id+')"';
                                html += 'class="del_comment">删除</a>';
                            }
                        html += '</span>';
                        html += '</p></div></div>';
                    }
                    $(comment.box).append(html);
                    $('.loading').remove();
                    $('.J-reply-comment').on('click', function() {
                        var attrs = urlToObject($(this).data('args'));
                        comment.initReadReply(attrs);
                    });
                } else {
                    comment.canload = false;
                    $('.loading').html('暂无相关内容');
                }
            }
        });
    },

    // 显示编辑框
    display: function(attr) {
        var feedid = attr.row_id;
        var editor_box = $('#editor_box' + feedid);
        var comment_box = $('#comment_box' + feedid);
        if (editor_box.length > 0) {
            return false;
        }

        var boxhtml = '<div class="dyBox_comment" id="editor_box' + feedid + '">' +
            '<textarea placeholder="" class="comment-editor" onkeyup="checkNums(this, 255, \'nums\');"></textarea>' +
            '<div class="dy_company">' +
            /*'<span class="fs-14">'+
            '<svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>表情</span>'+*/
            '<span class="dy_cs">可输入<span class="nums">255</span>字</span>' +
            '<a href="javascript:;" class="dy_share a_link J-comment-feed' + feedid + '" to_uid="0" row_id=' + feedid + '>评论</a>' +
            '</div>' +
            '</div>';
        comment_box.prepend(boxhtml);
        if (attr.type != 'news') {
            $('.J-comment-feed' + feedid).on('click', function() {
                if (MID == 0) {
                    window.location.href = '/passport/index';
                    return false;
                }
                comment.addComment(null, this);
            });
        } else {
            $('.J-comment-feed' + feedid).on('click', function() {
                if (MID == 0) {
                    window.location.href = '/passport/index';
                    return false;
                }
                comment.addNewComment(null, this);
            });
        }

    },
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
            window.location.href = '/passport/index';
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
            beforeSend: function(xhr) {　　　 xhr.setRequestHeader('Authorization', TOKEN);　　 },
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
        var url = request_url.collect_feed.replace('{feed_id}', feed_id);
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            beforeSend: function(xhr) {　　　 xhr.setRequestHeader('Authorization', TOKEN);　　 },
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
            window.location.href = '/passport/index';
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
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
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
    // 关注
    $('.infR_time li').hover(function() {
        var type = $(this).attr('type');

        $(this).siblings().find('a').removeClass('hover');
        $(this).find('a').addClass('hover');

        $('.dyrBottom div').hide();
        $('#' + type).show();
    })

    $('.dyn_huan').on('click', function() {
        $('#cover').click();
    })

    $('#cover').on('change', function(e) {
        var file = e.target.files[0];
        fileUpload.init(file, uploadPccover);
    });

    // $('.dyn_top').hover(function() {
    //     $('.dyn_huan').show();
    // }, function() {
    //     $('.dyn_huan').hide();
    // });

    // 微博操作菜单
    $('#feeds-list').on('click', '.show_admin', function() {
        if ($(this).next('.cen_more').css('display') == 'none') {
            $(this).next('.cen_more').show();
        } else {
            $(this).next('.cen_more').hide();
        }
    });
    // 微博操作菜单
    $('#content-list').on('click', '.show_admin', function() {
        if ($(this).next('.cen_more').css('display') == 'none') {
            $('.cen_more').hide();
            $(this).next('.cen_more').show();
        } else {
            $(this).next('.cen_more').hide();
        }
    });

    // 显示回复框
    $('#feeds-list, #article-list, #content-list').on('click', '.J-comment-show', function() {
        if (MID == 0) {
            window.location.href = '/passport/index';
            return;
        }
        var attrs = urlToObject($(this).data('args'));
        if ($(attrs.box).css('display') == 'none') {
            $(attrs.box).show();
            comment.display(attrs);
        } else {
            $(attrs.box).hide();
        }
    });

    // 回复初始化
    $('#feeds-list, #content-list').on('click', '.J-reply-comment', function() {
        var attrs = urlToObject($(this).data('args'));
        comment.initReply(attrs);
    });

})

var uploadPccover = function(image, f, task_id) {
    $.ajax({
        url: '/api/v1/users',
        type: 'PATCH',
        data: { cover_storage_task_id: task_id },
        dataType: 'json',
        beforeSend: function(xhr) {　　　 xhr.setRequestHeader('Authorization', TOKEN);　　 },
        success: function(res) {
            noticebox('更换背景图成功', 1);
            $('.dynTop_bg').attr('src', image.src);
        }
    });
}
