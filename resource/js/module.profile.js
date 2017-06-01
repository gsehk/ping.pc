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
    this.setting.maxid = option.maxid || 0; // 最大微博ID
    this.setting.loadlimit = option.loadlimit || 10; // 每次加载的数目，默认为10
    this.setting.user_id = option.user_id || 0; //  用户user_id
    this.setting.type = option.type || 'all'; //  微博分类
    this.setting.canload = option.canload || true; // 是否能加载
    this.setting.loading = option.loading || '.dy_cen'; //加载图位置
    this.setting.page = option.page || 1; // 页码
    if (option.type) {
        switch (option.type) {
            /* 全部动态 */
            case 'all':
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
                break;
                /* img type */
            case 'img':
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
                break;
                /* === */
            default:
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
        }
    }

    weibo.bindScroll();

    if ($(weibo.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $(weibo.setting.loading).after(loadHtml);
        // $(weibo.setting.container).append(loadHtml);
        weibo.loadMore();
    }
};
/**
 * 页面底部触发事件
 * @return void
 */
weibo.bindScroll = function() {
    // 底部触发事件绑定
    $(window).bind('scroll resize', function() {

        // 加载指定次数后，将不能自动加载
        if (weibo.isLoading()) {
            var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
            var bodyHeight = $(document.body).height();
            if (bodyTop + $(window).height() >= bodyHeight - 250) {
                if ($(weibo.setting.container).length > 0) {
                    $('.loading').remove();
                    $(weibo.setting.loading).after(loadHtml);
                    // $(weibo.setting.container).append(loadHtml);
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
    postArgs.max_id = weibo.setting.maxid;
    postArgs.limit = weibo.setting.loadlimit;
    postArgs.type = weibo.setting.type;
    postArgs.page = weibo.setting.page;
    $.ajax({
        url: weibo.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error: function(xml) {},
        success: function(res) {
            if (res.data.maxid > 0) {
                weibo.setting.canload = true;
                // 修改加载ID
                weibo.setting.page++;
                weibo.setting.maxid = res.data.maxid;
                var html = res.data.html;
                if (weibo.setting.loadcount == 1) {
                    $(weibo.setting.container).html(html);
                    $('.loading').remove();
                } else {
                    $(weibo.setting.container).append(html);
                }
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
    layer.confirm(confirmTxt, {}, function() {
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
                }else{
                    layer.msg(res.message, { icon: 0 });
                }
                
            }
        });
        layer.close(index);
    });
};

var news = {};

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
    this.setting.maxid = option.maxid || 0; // 最大文章ID
    this.setting.loadlimit = option.loadlimit || 10; // 每次加载的数目，默认为10
    this.setting.user_id = option.user_id || 0; //  用户user_id
    this.setting.type = option.type || 0; //  文章分类
    this.setting.canload = option.canload || true; // 是否能加载
    this.setting.loading = option.loading || '.dy_cen'; //加载图位置
    this.setting.page = option.page || 1; // 页码

    if (option.type == 'feed' || option.type == 'news') {
        this.setting.url = request_url.get_user_collect.replace('{user_id}', option.user_id);
    } else {
        this.setting.url = request_url.get_user_news.replace('{user_id}', option.user_id);
    }
    news.bindScroll();

    if ($(news.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $(news.setting.loading).after(loadHtml);
        // $(news.setting.container).append(loadHtml);
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
            var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
            var bodyHeight = $(document.body).height();
            if (bodyTop + $(window).height() >= bodyHeight - 250) {
                if ($(news.setting.container).length > 0) {
                    $(news.setting.loading).after(loadHtml);
                    // $(news.setting.container).append(loadHtml);
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

    var postArgs = {};
    postArgs.max_id = news.setting.maxid;
    postArgs.limit = news.setting.loadlimit;
    postArgs.type = news.setting.type;
    postArgs.page = news.setting.page;

    $.ajax({
        url: news.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error: function(xml) {},
        success: function(res) {
            if (res.data.maxid > 0) {
                news.setting.canload = true;
                // 修改加载ID
                news.setting.page++;
                news.setting.maxid = res.data.maxid;
                var html = res.data.html;
                if (news.setting.loadcount == 1) {
                    $(news.setting.container).html(html);
                    $('.loading').remove();
                } else {
                    $(news.setting.container).append(html);
                }
            } else {
                news.setting.canload = false;
                if (news.setting.loadcount == 1) {
                    no_data(news.setting.container, 1, ' 暂无相关内容');
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
            noticebox('请登录', 0, '/passport/index');
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = request_url.digg_feed.replace('{feed_id}', feed_id);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
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

        var url = request_url.digg_feed.replace('{feed_id}', feed_id);
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
        this.max_id = attrs.max_id || 0;
        this.limit = attrs.limit || 5;
        this.to_uid = attrs.to_uid || 0;
        this.canload = attrs.canload || 1;
        this.reply_to_user_id = attrs.reply_to_user_id || 0;
        this.addToEnd = attrs.addToEnd || 0;
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
                var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
                var bodyHeight = $(document.body).height();
                if (bodyTop + $(window).height() >= bodyHeight - 250) {
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
        var url = request_url.get_feed_commnet.replace('{feed_id}', comment.row_id);
        $.ajax({
            url: url,
            type: 'GET',
            data: { max_id: comment.max_id, limit: comment.limit },
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.data.length > 0) {
                    comment.canload = true;
                    comment.max_id = res.data[res.data.length - 1].id;
                    var data = res.data,
                        html = '';
                    for (var i in data) {
                        var avatar = data[i].uinfo.avatar ? API + '/storages/' + data[i].uinfo.avatar : AVATAR;
                        html += '<div class="delComment_list">';
                        html += '<div class="comment_left">';
                        html += '<img src="' + avatar + '" class="c_leftImg" />';
                        html += '</div>';
                        html += '<div class="comment_right">';
                        html += '<span class="del_ellen">' + data[i].uinfo.name + '</span>';
                        html += '<span class="c_time">' + data[i].created_at + '</span>';
                        html += '<i class="icon iconfont icon-gengduo-copy"></i>';
                        html += '<p>' + data[i].comment_content + '';
                        if (data[i].user_id != MID) {
                            html += '<span class="del_huifu">';
                            html += '<a href="javascript:void(0)" data-args="editor=#mini_editor&box=#comment_detail&to_comment_uname=' + data[i].uinfo.name + '&canload=0&to_uid=' + data[i].user_id + '"';
                            html += 'class="J-reply-comment">回复';
                            html += '</a></span>';
                        }
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
                comment.addComment(null, this);
            });
        } else {
            $('.J-comment-feed' + feedid).on('click', function() {
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
        var content = _textarea.value;

        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }

        var url = request_url.feed_comment.replace('{feed_id}', feedid);

        obj.innerHTML = '回复中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: { comment_content: content, reply_to_user_id: to_uid },
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
                    if (obj != undefined) {
                        obj.innerHTML = '回复';
                    }
                    var html = '<p><span>' + NAME + '：</span>' + content + '</p>';
                    var commentBox = $('.comment_box' + feedid);
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                    }
                } else {
                    alert(res.message);
                }
            }
        });
    },
    // 详情发表评论
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
        var content = _textarea.value;

        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }

        var url = request_url.comment_news.replace('{news_id}', news_id);
        // console.log(url, content, to_uid);
        obj.innerHTML = '回复中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: { comment_content: content, reply_to_user_id: to_uid },
            dataType: 'json',
            error: function(xml) {},
            success: function(res) {
                if (res.status == true) {
                    if (obj != undefined) {
                        obj.innerHTML = '回复';
                    }
                    var html = '<p><span>' + NAME + '：</span>' + content + '</p>';
                    var commentBox = $('.comment_box' + news_id);
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                    }
                } else {
                    alert(res.message);
                }
            }
        });
    },
    delComment: function(comment_id) {
        $.post(U('widget/Comment/delcomment'), { comment_id: comment_id }, function(msg) {
            // 动态添加字数
            var commentDom = $('#feed' + comment.row_id).find('a[event-node="comment"]');
            var oldHtml = commentDom.html();
            if (oldHtml != null) {
                var commentVal = oldHtml.replace(/\(\d+\)$/, function(num) {
                    var cnum = parseInt(num.slice(1, -1)) - 1;
                    if (cnum <= 0) {
                        return '';
                    }
                    num = '(' + cnum + ')';
                    return num;
                });
                commentDom.html(commentVal);
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
            noticebox('请登录', 0, '/passport/index');
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
                    alert(res.message);
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
                    alert(res.message);
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
        fileUpload(file, uploadPccover);
    });

    $('.dyn_top').hover(function() {
        $('.dyn_huan').show();
    }, function() {
        $('.dyn_huan').hide();
    });

    // 微博操作菜单
    /*$('#feeds-list').on('click', '.show_admin', function() {
        if ($(this).next('.cen_more').css('display') == 'none') {
            $(this).next('.cen_more').show();
        } else {
            $(this).next('.cen_more').hide();
        }
    });*/
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
        var attrs = urlToObject($(this).data('args'));
        if ($(attrs.box).css('display') == 'none') {
            $(attrs.box).show();
            comment.display(attrs);
        } else {
            $(attrs.box).hide();
        }
    });

    // 回复初始化
    $('#feeds-list').on('click', '.J-reply-comment', function() {
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
        success: function(res) {
            noticebox('更换背景图成功', 1);
            $('.dynTop_bg').attr('src', image.src);
        }
    });
}
