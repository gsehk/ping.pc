var weibo = {};

/**
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="weibo.showImg();" src="' + SITE_URL + '/api/v2/files/' + task_id + '"/ tid="' + task_id + '" amount="">';
    var del = '<span class="imgdel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></span>'
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
    // 登录判断
    if (!checkLogin()) return false;

    // 付费免费
    var select = $('#feed_select').data('value');

    if (select == 'pay') {
        if ($('.feed_picture').find('img').length > 0) { // 图片付费弹窗
            var pay_box = '<div class="feed_pay_box"><p class="pay_title">付费设置</p>';
            var images_box = '<div class="pay_images">';
            var info_box = '';
            $('.feed_picture').find('img').each(function(index) {
                var amount = $(this).attr('amount') != '' ? $(this).attr('amount') : '';

                var svg = amount == '' ? '' : '<svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>';
                images_box += '<div class="pay_image"><img ' + (index == 0 ? 'class="current"' : '') + 'src="' + $(this).attr('src') + '" tid="' + $(this).attr('tid') + '" amount="' + amount + '"/>' + svg + '</div>';

                // 如果为第一张图，添加付费信息
                if (index == 0){
                    // 付费信息
                    info_box = '<div class="pay_info"><div class="pay_head clearfix">'
                                    + '<span class="pay_text">设置图片收费金额</span>'
                                    + '<span class="pay_btn pay_btn_yes">确定</span>'
                                    + '<span class="pay_btn pay_btn_reset">重置</span>'
                                + '</div>';

                    info_box +=  '<div class="pay_body">'
                                    + '<span' + (amount == '1' ? ' class="current"' : '') + ' amount="1">￥1</span>'
                                    + '<span' + (amount == '5' ? ' class="current"' : '') + ' amount="5">￥5</span>'
                                    + '<span' + (amount == '10' ? ' class="current"' : '') + ' amount="10">￥10</span>'
                                    + '<input type="number" placeholder="自定义金额，必须为整数" value="' + (amount != '1' && amount != '5' && amount != '10' ? amount : '') + '">'
                                +'</div>';

                }
            });
            images_box += '<div class="triangle"></div></div>';
            info_box += '</div>'

            var html = pay_box + images_box + info_box + '</div>';
        } else { // 文字付费弹窗

        }
        ly.confirm(html, '', '', weibo.doPostFeed('pay'))
    }
};

weibo.doPostFeed = function(type) {
    // 分享字数
    var strlen = getLength(data.feed_content);
    var leftnums = initNums - strlen;
    if (leftnums < 0 || leftnums == initNums) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }

    var images = [];
    if (type == 'free') { // 免费
        $('.feed_picture').find('img').each(function() {
            images.push({'id':$(this).attr('tid')});
        });
    } else {  // 付费
        $('.pay_images').find('img').each(function() {
            images.push({'id':$(this).attr('tid'), 'type': 'read', amount:$(this).attr('amount')});
        });
    }

    var data = {
        feed_content: $('#feed_content').val(),
        images: images,
        feed_from: 1,
        feed_mark: MID + new Date().getTime(),
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
        error: function(xhr){
            showError(xhr.responseJSON);
        }
    });
}

weibo.afterPostFeed = function(feed_id) {
    var url = '/feeds';
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
                $('#feed' + feed_id).fadeOut();
                layer.closeAll();
            },
            error: function(xhr){
                showError(xhr.responseJSON);
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
        var url = '';
        $.ajax({
            url: url,
            type: 'POST',
            data: { aid: feed_id, to_uid: to_uid, reason: val, from: 'weibo' },
            dataType: 'json',
            success: function(res) {
                layer.msg(' 举报成功', { icon: 1 });
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
        layer.close(index);
    });
};
weibo.pinneds = function (feed_id) {
    var url = '/api/v2/feeds/'+feed_id+'/pinneds';
    pinneds(url);
};

/**
 * 核心评论对象
 */
var comment = {
    // 初始化回复操作
    initReply: function(obj) {
        var attrs = urlToObject($(obj).data('args'));
        $('.J-comment-feed' + attrs.row_id).attr('to_uid', attrs.to_uid);
        var editor = $('#comment_box' + attrs.row_id).find('textarea');
        var html = '回复@' + attrs.to_uname + ' ：';
        //清空输入框
        editor.val('');
        editor.val(html);
        editor.focus();
    },
    // 初始化回复操作
    reply: function(obj) {
        $('#J-comment-feed').attr('to_uid', obj.user_id);
        var editor = $('#mini_editor');
        var html = '回复@' + obj.user.name + ' ：';
        //清空输入框
        editor.val('');
        editor.val(html);
        editor.focus();
    },

    // 列表发表评论
    publishs: function(obj) {
        if (!checkLogin()) return false;

        var to_uid = $(obj).attr('to_uid') || 0;
        var rowid = $(obj).attr('row_id') || 0;
        var editor = $('#comment_box'+rowid).find('textarea');

        var formData = { body: editor.val() };
        if (!editor.val()) {
            layer.msg('评论内容不能为空');return;
        }
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = '/api/v2/feeds/' + rowid + '/comments';
        obj.innerHTML = '评论中..';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (obj != undefined) {
                    obj.innerHTML = '评论';
                    $('.nums').text(initNums);
                    $('.cs'+rowid).text(parseInt($('.cs'+rowid).text())+1);
                    editor.val('');
                }
                var html = '<p class="comment_con" id="comment'+res.comment.id+'">';
                    html +=     '<span class="tcolor">' + NAME + '：</span>' + formData.body + '';
                    html +=     '<a class="del_comment" onclick="comment.delete('+res.comment.id+', '+rowid+');">删除</a>';
                    html += '</p>';

                $('#comment-list'+rowid).prepend(html);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
                obj.innerHTML = '评论';
            }
        });
    },
    // 详情发表评论
    publish: function(obj) {
        if (!checkLogin()) return false;

        var to_uid = $(obj).attr('to_uid') || 0;
        var rowid = $(obj).attr('row_id') || 0;
        var editor = $('#mini_editor');

        var formData = { body: editor.val() };
        if (!editor.val()) {
            layer.msg('评论内容不能为空');return;
        }
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = '/api/v2/feeds/'+rowid+'/comments';
        obj.innerHTML = '评论中..';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res, data, xml) {
                if (obj != undefined) {
                    obj.innerHTML = '评论';
                    $('.nums').text(initNums);
                    $('#J-comment-feed').attr('to_uid', 0);
                    editor.val('');
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

                $('#comment_box').prepend(html);
                $('.comment_count').text(parseInt($('.comment_count').text())+1);
                $('.J-reply-comment').on('click', function() {
                    comment.initReply();
                });
            },
            error: function(xhr){
                showError(xhr.responseJSON);
                obj.innerHTML = '评论';
            }
        });
    },
    delete: function(comment_id, feed_id) {
        var url = '/api/v2/feeds/' + feed_id + '/comments/' + comment_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#comment'+comment_id).fadeOut();
                if (undefined != typeof($('.cs' + feed_id))) {
                    $('.cs' + feed_id).text(parseInt($('.cs' + feed_id).text())-1);
                }
                if (undefined != typeof($('.comment_count'))) {
                    $('.comment_count').text(parseInt($('.comment_count').text())-1);
                }
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    },
    // 评论申请置顶
    pinneds: function (obj){
        var url = '';
        if (obj.commentable_type == 'feeds') {
            url = '/api/v2/feeds/'+obj.commentable_id+'/comments/'+obj.id+'/pinneds';
            pinneds(url);
        }
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
        checkLogin();
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/like';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function(res, data, xml) {
                $digg = $('#digg' + feed_id);
                var num = $digg.attr('rel');
                num++;
                $digg.attr('rel', num);
                if (page == 'read') {
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg(' + feed_id + ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>' + num + '</font></a>');
                }
                digg.digglock = 0;
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
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
            success: function(res, data, xml) {
                $digg = $('#digg' + feed_id);
                var num = $digg.attr('rel');
                num--;
                $digg.attr('rel', num);
                if (page == 'read') {
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg(' + feed_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>' + num + '</font></a>');
                }

                digg.digglock = 0;
            },
            error: function(xhr) {
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
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
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
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
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

    // 显示回复框
    $('#feeds_list').on('click', '.J-comment-show', function() {
        if (!checkLogin()) return false;

        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });

    // 付费图片弹窗
    $('#feeds_list').on('click', '.feed_image_pay', function() {
        var _this = $(this);
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }
        var amount = _this.data('amount');
        var node = _this.data('node');
        var file = _this.data('file');
        var image = _this.data('original');

        var html = '<div class="exit_money">￥' + amount + '</div>您只需要支付￥' + amount + '元即可查看高清大图，是否确认支付？';
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/purchases/' + node;
            // 确认支付
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    var img = '<img class="lazy per_image" data-original="' + image + '"/>';
                    _this.parent().replaceWith(img);
                    $("img.lazy").lazyload({ effect: "fadeIn" });
                    noticebox('支付成功', 1);
                },
                error: function(xhr) {
                    showError(xhr.responseJSON);
                }
            });
        })
    });

    // 付费设置确认
    $('body').on('click', '.pay_btn_yes', function() {
        // 输入框输入值
        var amount = $('.pay_body input').val();
        // 选择值
        var span_amount = $('.pay_body .current').attr('amount');
        if (amount == '' && typeof(span_amount) == 'undefined') {
            return false;
        }
        var real = amount == '' ? span_amount : amount;

        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', real);
        $('.feed_picture img').eq(index).attr('amount', real);

        // 添加标示
        $('.pay_images .pay_image').eq(index).append('<svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>');
    });

    $('body').on('click', '.pay_btn_reset', function() {
        // 选择图片索引
        var index = $('.pay_image .current').parent().index();

        // 设置金额
        $('.pay_images .pay_image').eq(index).find('img').attr('amount', '');
        $('.feed_picture img').eq(index).attr('amount', '');

        // 添加标示
        $('.pay_images .pay_image').eq(index).find('svg').remove();

        $('.pay_body span').removeClass('current');
        $('.pay_body input').val('');
    });

    // 付费图片点击
    $('body').on('click', '.pay_images img', function() {
        $(this).parents('.pay_images').find('img').removeClass('current');
        $(this).addClass('current');

        var amount = $(this).attr('amount');

        $('.pay_body').find('span').removeClass('current');
        $('.pay_body').find('input').val('');
        if (amount != '') {
            if (amount == '1') {
                $('.pay_body span[amount="1"]').addClass('current');
            } else if (amount == '5') {
                $('.pay_body span[amount="5"]').addClass('current');
            } else if (amount == '10') {
                $('.pay_body span[amount="10"]').addClass('current');
            } else {
                $('.pay_body input').val(amount);
            }
        }
    });

    // 收费金额选择
    $('body').on('click', '.pay_body span', function() {
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
        $(this).parent().find('input').val('');
    });

    // 收费金额输入
    $('body').on('focus', '.pay_body input', function() {
        $(this).parent().find('span').removeClass('current');
    });
});
