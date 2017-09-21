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
    checkLogin()

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
                                    + '<input min="1" oninput="if(value.length>8)value=value.slice(0,8)"  type="number" placeholder="自定义金额，必须为整数" value="' + (amount != '1' && amount != '5' && amount != '10' ? amount : '') + '">'
                                +'</div>';

                }
            });
            images_box += '<div class="triangle"></div></div>';
            info_box += '</div>'

            var html = pay_box + images_box + info_box + '</div>';
        } else { // 文字付费弹窗
            var amount = $('#feed_content').attr('amount') != '' ? $('#feed_content').attr('amount') : '';
            var pay_box = '<div class="feed_pay_box"><p class="pay_title">付费设置</p>';
            var info_box = '';
            info_box = '<div class="pay_info"><div class="pay_head clearfix">'
                            + '<span class="pay_text">设置文字收费金额</span>'
                        + '</div>';

            info_box +=  '<div class="pay_body">'
                            + '<span' + (amount == '1' ? ' class="current"' : '') + ' amount="1">￥1</span>'
                            + '<span' + (amount == '5' ? ' class="current"' : '') + ' amount="5">￥5</span>'
                            + '<span' + (amount == '10' ? ' class="current"' : '') + ' amount="10">￥10</span>'
                            + '<input min="1" oninput="if(value.length>8)value=value.slice(0,8)"  type="number" placeholder="自定义金额，必须为整数" value="' + (amount != '1' && amount != '5' && amount != '10' ? amount : '') + '">'
                        +'</div>';
            info_box += '</div>'

            var html = pay_box + info_box + '</div>';
        }
        ly.confirm(html, '', '', function(){
            weibo.doPostFeed('pay');
        });
    } else {
        weibo.doPostFeed('free');
    }
};

weibo.doPostFeed = function(type) {
    // 分享字数限制
    var strlen = getLength($('#feed_content').val());
    var leftnums = initNums - strlen;
    if (leftnums < 0 || leftnums == initNums) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }


    // 组装数据
    var data = {
        feed_content: $('#feed_content').val(),
        feed_from: 1,
        feed_mark: MID + new Date().getTime(),
    }
    var images = [];
    if (type == 'free') { // 免费
        $('.feed_picture').find('img').each(function() {
            images.push({'id':$(this).attr('tid')});
        });
        if (images.length != 0) data.images = images;
    } else {  // 付费
        // 图片付费
        $('.pay_images').find('img').each(function() {
            var amount = $(this).attr('amount');
            if (amount == '') {
                images.push({'id':$(this).attr('tid')});
            } else {
                images.push({'id':$(this).attr('tid'), 'type': 'read', 'amount': amount});
            }
        });
        if (images.length != 0) data.images = images;

        // 文字付费
        var amount = $('#feed_content').attr('amount');
        if (amount != '') data.amount = amount;
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
//微博申请置顶
weibo.pinneds = function (feed_id) {
    var url = '/api/v2/feeds/'+feed_id+'/pinneds';
    pinneds(url);
};
weibo.addComment = function (obj, type) {
    var row_id = type ? obj.id : obj;
    var url = '/api/v2/feeds/' + row_id + '/comments';
    comment.support.row_id = row_id;
    comment.support.position = type;
    comment.support.editor = $('#J-editor'+row_id);
    comment.support.button = $('#J-button'+row_id);
    comment.publish(url, function(res){
        $('.nums').text(comment.support.wordcount);
        $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
    });
};

var liked = {
    init: function(row_id, cate, type){
        checkLogin();
        this.row_id = row_id || 0;
        this.type = type || 0;
        this.cate = cate || '';
        this.box = $('#J-likes'+row_id);
        this.num = this.box.attr('rel');
        this.status = this.box.attr('status');
        this.res = this.get_link();

        if (parseInt(this.status)) {
            this.unlike();
        } else {
            this.like();
        }
    },
    like: function(row_id, cate, type) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        $.ajax({
            url: _this.res.link,
            type: 'POST',
            dataType: 'json',
            success: function() {
                _this.num ++;
                _this.lockStatus = 0;
                _this.box.attr('rel', _this.num);
                _this.box.attr('status', 1);
                _this.box.find('a').addClass('act');
                _this.box.find('font').text(_this.num);
                if (_this.type) {
                    _this.box.find('svg').html('<use xlink:href="#icon-xihuan-red"></use>');
                } else {
                    _this.box.find('svg').html('<use xlink:href="#icon-xihuan-white-copy"></use>');
                }

            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });

    },
    unlike: function(feed_id, page) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        $.ajax({
            url: _this.res.unlink,
            type: 'DELETE',
            dataType: 'json',
            success: function() {
                _this.num --;
                _this.lockStatus = 0;
                _this.box.attr('rel', _this.num);
                _this.box.attr('status', 0);
                _this.box.find('a').removeClass('act');
                _this.box.find('font').text(_this.num);
                _this.box.find('svg').html('<use xlink:href="#icon-xihuan-white"></use>');
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });
    },
    get_link: function(){
        var res = {};
        switch (this.cate) {
            case 'feeds':
                res.link = '/api/v2/feeds/' + this.row_id + '/like';
                res.unlink = '/api/v2/feeds/' + this.row_id + '/unlike';
                break;
            case 'news':
                res.link = '/api/v2/news/' + this.row_id + '/like';
                res.unlink = '/api/v2/news/' + this.row_id + '/unlike';
            break;
        }

        return res;
    }
};

var collected = {
    init: function(row_id, cate, type){
        checkLogin();
        this.row_id = row_id || 0;
        this.type = type || 0;
        this.cate = cate || '';
        this.box = $('#J-collect'+row_id);
        this.num = this.box.attr('rel');
        this.status = this.box.attr('status');
        this.res = this.get_link();

        if (parseInt(this.status)) {
            this.uncollect();
        } else {
            this.collect();
        }
    },
    collect: function(row_id, cate, type) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        $.ajax({
            url: _this.res.link,
            type: 'POST',
            dataType: 'json',
            success: function() {
                _this.num ++;
                _this.lockStatus = 0;
                _this.box.attr('rel', _this.num);
                _this.box.attr('status', 1);
                _this.box.find('a').addClass('act');
                _this.box.find('font').text(_this.num);
                _this.box.find('svg').html('<use xlink:href="#icon-shoucang-copy"></use>');
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });

    },
    uncollect: function(feed_id, page) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        $.ajax({
            url: _this.res.unlink,
            type: 'DELETE',
            dataType: 'json',
            success: function() {
                _this.num --;
                _this.lockStatus = 0;
                _this.box.attr('rel', _this.num);
                _this.box.attr('status', 0);
                _this.box.find('a').removeClass('act');
                _this.box.find('font').text(_this.num);
                _this.box.find('svg').html('<use xlink:href="#icon-shoucang-copy1"></use>');
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });
    },
    get_link: function(){
        var res = {};
        switch (this.cate) {
            case 'feeds':
                res.link = '/api/v2/feeds/' + this.row_id + '/collections';
                res.unlink = '/api/v2/feeds/' + this.row_id + '/uncollect';
                break;
            case 'news':
                res.link = '/api/v2/news/' + this.row_id + '/like';
                res.unlink = '/api/v2/news/' + this.row_id + '/uncollect';
            break;
        }

        return res;
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
        checkLogin();

        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });

    // 付费图片弹窗
    $('#feeds_list, .detail_images').on('click', '.feed_image_pay', function() {
        checkLogin();

        var _this = $(this);
        var amount = _this.data('amount');
        var node = _this.data('node');
        var file = _this.data('file');
        var image = _this.data('original');

        var html = formatConfirm('购买支付', '<div class="confirm_money">￥' + amount + '</div>您只需要支付￥' + amount + '元即可查看高清大图，是否确认支付？');
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

    // 显示跳转详情文字
    $('#feeds_list').on("mouseover mouseout", '.date', function(event){
        if(event.type == "mouseover"){
          var width = $(this).find('span').first().width();
          $(this).find('span').first().hide();
          $(this).find('span').last().css({display:'inline-block', width: width});
          $(this).find('span').last().css({minWidth:'50px'});
        }else if(event.type == "mouseout"){
          $(this).find('span').first().show();
          $(this).find('span').last().hide();
        }
    })

    // 文字弹窗
    $('#feeds_list').on('click', '.feed_pay_text', function() {
        checkLogin()

        var _this = $(this);
        var amount = _this.data('amount');
        var node = _this.data('node');

        var html = formatConfirm('购买支付', '<div class="confirm_money">￥' + amount + '</div>您只需要支付￥' + amount + '元即可查看完整内容，是否确认支付？');
        ly.confirm(html, '', '', function(){
            var url = '/api/v2/purchases/' + node;
            // 确认支付
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
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

        // 三角位置
        var left = $(this).parent().position().left + 3;
        $(this).parents('.pay_images').find('.triangle').css('margin-left', left);
    });

    // 收费金额选择
    $('body').on('click', '.pay_body span', function() {
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
        $(this).parent().find('input').val('');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).attr('amount'));
        }
    });

    // 收费金额输入
    $('body').on('focus change', '.pay_body input', function() {
        $(this).parent().find('span').removeClass('current');

        // 若为文字付费
        if ($('.pay_images').length == 0) {
            $('#feed_content').attr('amount', $(this).val());
        }
    });
});
