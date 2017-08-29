var weibo = {};

/**
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="weibo.showImg();" src="' + image.src + '" tid="' + task_id + '"/>';
    var del = '<span class="imgdel"><i class="icon iconfont icon-close"></i></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};
weibo.showImg = function(){
    layer.photos({
      photos: '#file_upload_1-queue'
      ,anim: 0 
      ,move: false
    });
};
/**
 * 发布动态
 * @return void
 */
weibo.postFeed = function() {
    if (MID == 0) {
        window.location.href = '/passport/login';
        return false;
    }

    var images = [];
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });

    var data = {
        feed_content: $('#feed_content').val(),
        images: images,
        feed_from: 1,
        feed_mark: MID + new Date().getTime(),
    }
    var strlen = getLength(data.feed_content);
        var leftnums = initNums - strlen;
        if (leftnums < 0 || leftnums == initNums) {
            noticebox('分享内容长度为1-' + initNums + '字', 0);
            return false;
        }
    $.ajax({
        url: '/api/v2/feeds',
        type: 'post',
        data: data,
        success: function(res) {
            noticebox('发布成功', 1);
            $('.feed_picture').html('').hide();
            $('#feed_content').val('');
            weibo.afterPostFeed(res.id);
        },
        error: function(xhr) {
            showError(xhr.respone);
        }

    })
};

weibo.afterPostFeed = function(feed_id) {
    var url = '/feeds/getfeed/';
    $.ajax({
        url: url,
        type: 'get',
        data: { feed_id: feed_id},
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
weibo.delFeed = function(feed_id) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url = '/api/v2/feeds/' + feed_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#feed' + feed_id).fadeOut(1000);
                layer.closeAll();
            },
            error: function() {
                layer.msg(' 删除失败请稍后再试', { icon: 0 });
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
                layer.msg(' 举报成功', { icon: 1 });
            }
        });
        layer.close(index);
    });
};
/**
 * 核心评论对象
 */
var comment = {
    // 初始化评论对象
    init: function(attrs) {
        this.row_id = attrs.row_id || 0;
        this.after = attrs.after || 0;
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
                var scrollTop = $(this).scrollTop();
                var scrollHeight = $(document).height();
                var windowHeight = $(this).height();
                if(scrollTop + windowHeight == scrollHeight){
                    if ($(comment.box).length > 0) {
                        $(comment.box).append(loadHtml);
                        comment.loadMore();
                    }
                }
            }
        });
    },
    // 分享详情加载评论列表
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
                        var avatar = data[i].user.avatar ? data[i].user.avatar : defaultAvatar;
                        html += '<div class="delComment_list comment'+data[i].id+'">';
                        html += '<div class="comment_left">';
                        html += '<a href="/profile/'+data[i].user_id+'"><img src="' + avatar + '" class="c_leftImg" /></a>';
                        html += '</div>';
                        html += '<div class="comment_right">';
                        html += '<a href="/profile/'+data[i].user_id+'"><span class="del_ellen">' + data[i].user.name + '</span></a>';
                        html += '<span class="c_time">' + data[i].created_at + '</span>';
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
                    $('.del_left .loading').remove();
                    $('.J-reply-comment').on('click', function() {
                        var attrs = urlToObject($(this).data('args'));
                        comment.initReadReply(attrs);
                    });
                } else {
                    comment.canload = false;
                    $('.del_left .loading').html('暂无相关内容');
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
            '<span class="dy_cs">可输入<span class="nums">255</span>字</span>' +
            '<a href="javascript:;" class="dy_share a_link J-comment-feed' + feedid + '" to_uid="0" row_id=' + feedid + '>评论</a>' +
            '</div>' +
            '</div>';
        comment_box.prepend(boxhtml);

        $('.J-comment-feed' + feedid).on('click', function() {
            if (MID == 0) {
                window.location.href = '/passport/login';
                return false;
            }
            comment.addComment(null, this);
        });
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
                    var html = '<p class="comment'+res.comment.id+' comment_con">';
                        html += '<span>' + NAME + '：</span>' + formData.body + '';
                        html += '<a class="fs-14 del_comment" onclick="comment.delComment('+res.comment.id+', '+feedid+');">删除</a>';
                        html += '</p>';
                    var commentBox = $('.comment_box' + feedid);
                    var commentNum = $('.cs' + feedid);
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                        commentNum.text(parseInt(commentNum.text())+1);
                    }
                } else {
                    alert(res.message);
                }
            }
        });
    },
    // 详情发表评论
    addReadComment: function(afterComment, obj) {
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
        var formData = {
            body: _textarea.value,
        };
        if (this.to_uid > 0) {
            formData.reply_user = this.to_uid;
        }        

        if ("undefined" != typeof(this.addReadComment) && (this.addReadComment == true)) {
            return false; //不要重复评论
        }
        var addToEnd = this.addToEnd;
        var url = request_url.feed_comment.replace('{feed_id}', this.row_id);
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
                    var html = '<div class="delComment_list comment'+res.comment.id+'">';
                        html += '<div class="comment_left">';
                        html += '<a href="/profile/index?user_id='+MID+'"><img src="'+AVATAR+'" class="c_leftImg" /></a>';
                        html += '</div>';
                        html += '<div class="comment_right">';
                        html += '<a href="/profile/index?user_id='+MID+'"><span class="del_ellen">' + NAME + '</span></a>';
                        html += '<span class="c_time">刚刚</span>';
                        /*html += '<i class="icon iconfont icon-gengduo-copy"></i>';*/
                        html += '<p class="comment_con">' + formData.body + '';
                        html += '<a href="javascript:void(0)" onclick="comment.delComment('+res.comment.id+', '+comment.row_id+')"';
                        html += 'class="del_comment">删除</a>';
                        html += '</p></div></div>';
                    var commentBox = $(comment.box);
                    if ("undefined" != typeof(commentBox)) {
                        if (addToEnd == 1) {
                            commentBox.append(html);
                        } else {
                            commentBox.prepend(html);
                        }
                        _textarea.value = '';
                        $('.nums').text(initNums);
                        /*绑定回复操作*/
                        $('.J-reply-comment').on('click', function() {
                            comment.initReply();
                        });
                    }
                    var commentNum = $('.comment_count').text();
                    $('.comment_count').text(parseInt(commentNum)+1);
                } else {
                    alert(res.message);
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
            error: function(xml) {noticebox('删除失败请重试', 0);},
            success: function(res) {
                $('.comment'+comment_id).fadeOut(1000);
                var commentNum = $('.comment_count').text();
                $('.comment_count').text(parseInt(commentNum)-1);
                var nums = $('.cs' + feed_id);
                nums.text(parseInt(nums.text())-1);
            }
        });
    }
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
            window.location.href = '/passport/login';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = 'api/v2/feeds/' + feed_id + '/like';
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

        var url = 'api/v2/feeds/' + feed_id + '/unlike';
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

        var url = '/api/v2/feeds/' + feed_id + '/collections';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + feed_id);
                var num = $collect.attr('rel');
                num++;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect(' + feed_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">' + num + '</font>人收藏</a>');
                } else {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect(' + feed_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏</a>');
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
        var url = '/api/v2/feeds/' + feed_id + '/uncollect'
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + feed_id);
                var num = $collect.attr('rel');
                num--;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect(' + feed_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">' + num + '</font>人收藏</a>');
                } else {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏</a>');
                }
                collect.collectlock = 0;
            }
        });
    }
};

$(function() {

    // 图片删除事件
    $(".feed_post").on("click", ".imgdel", function() {
        $(this).parent().remove();
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
            $('.uploadify-queue-add').remove();
            $('#file_upload_1-queue').hide();
        }
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length != 0  && $('.uploadify-queue-add').length == 0 ){
            var add = '<a class="feed_picture_span uploadify-queue-add"></a>'
            $('.uploadify-queue').append(add);
        }
    });

    // 微博分类tab
    $('.show_tab a').on('click', function() {
        var type = $(this).data('type');
        $('#feeds_list').html('');
        weibo.init({ container: '#feeds_list', type: type });
        $('.show_tab a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });
    
    // 微博操作菜单
    $('#feeds_list').on('click', '.options', function() {
        if ($(this).next('.options_div').css('display') == 'none') {
            $('.options_div').hide();
            $(this).next('.options_div').show();
        } else {
            $(this).next('.options_div').hide();
        }
    });

    // 显示回复框
    $('#feeds_list').on('click', '.J-comment-show', function() {
        if (MID == 0) {
            window.location.href = '/passport/login';
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
    $('#feeds_list').on('click', '.J-reply-comment', function() {
        var attrs = urlToObject($(this).data('args'));

        comment.initReply(attrs);
    });
});
