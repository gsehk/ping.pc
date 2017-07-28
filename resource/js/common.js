var initNums = 255;
var loadHtml = "<div class='loading'><img src='" + RESOURCE_URL + "/images/loading.png' class='load'>加载中</div>";
var confirmTxt = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shibai-copy"></use></svg>';
var mark_time = MID + new Date().getTime();
var request_url = {
    /* 登录 */
    login: '/passport/index',
    /* 注销 */
    logout: '/passport/logout',
    /* 投稿 */
    contribute: '/api/v2/news/categories/{category}/news',
    /* 获取文章列表 */
    get_news: '/information/getNewsList',
    /* 文章点赞 */
    digg_news: '/api/v1/news/{news_id}/digg',
    /* 文章收藏  */
    collect_news: '/api/v1/news/{news_id}/collection',
    /* 评论文章 */
    comment_news: '/api/v2/news/{news_id}/comments',
    /* 删除文章评论 */
    del_news_comment: '/api/v2/news/{news_id}/comments/{comment_id}',    
    // 获取文章评论
    get_comment: '/news/{news_id}/comments',
    /* 分享 */
    feeds: '/api/v2/feeds',
    /* 分享列表 */
    'feeds_list': '/feeds',
    /* 获取附件 */
    images: '/api/v2/files/',
    /* 分享评论 */
    feed_comment: '/api/v2/feeds/{feed_id}/comments',
    /* 获取分享评论 */
    feed_commnets: '/feeds/{feed_id}/comments',    
    /* 删除分享评论  */
    del_feed_comment: '/api/v2/feeds/{feed_id}/comments/{comment_id}',
    /* 删除分享 */
    delete_feed: '/api/v2/feeds/{feed_id}',
    /*  举报分享  */
    denounce_feed: '/feed/{feed_id}/denounce',
    /* 分享点赞 */
    feed_like: '/api/v2/feeds/{feed_id}/like',
    /* 分享点赞 */
    feed_unlike: '/api/v2/feeds/{feed_id}/unlike',
    /* 收藏分享  */
    collect_feed: '/api/v2/feeds/{feed_id}/collections',
    /* 删除分享*/
    del_collect_feed: '/api/v2/feeds/{feed_id}/uncollect',
    /* 获取用户的分享 */
    get_user_feed: '/profile/feeds',
        
    /* 获取用户的文章 */
    get_user_news: '/profile/news',
    /* 获取动态收藏 */
    get_feed_collect: '/feeds/collection',
    get_news_collect: '/news/collection',
    
};

/**
 * Ajax 设置csrf Header
 * @type {Object}
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        'Authorization': 'Bearer '+TOKEN,
    }
});

/**
 * 创建一个存储对象
 * @type {Object}
 */
var args = {
    data: {},
    set: function(name, value) {
        this.data[name] = value;
        return this;
    },
    get: function() {
        return this.data;
    }
};

/**
 * url参数转换为对象
 * @param  string url a=1&b=2&c=3
 * @return {Object}
 */
var urlToObject = function(url) {
    var urlObject = {};
    var urlString = url.substring(url.indexOf("?") + 1);
    var urlArray = urlString.split("&");
    for (var i = 0, len = urlArray.length; i < len; i++) {
        var urlItem = urlArray[i];
        var item = urlItem.split("=");
        urlObject[item[0]] = item[1];
    }

    return urlObject;
};

/**
 * 字符串长度计算 - 中文和全角符号为1；英文、数字和半角为0.5
 * @param  string str      字符串
 * @param  bool shortUrl 
 * @return int
 */
var getLength = function(str, shortUrl) {
    str = str || '';
    if (true == shortUrl) {
        // 一个URL当作十个字长度计算
        return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
            .replace(/^\s+|\s+$/ig, '').replace(/[^\x00-\xff]/ig, 'xx').length / 2);
    } else {
        return Math.ceil(str.replace(/^\s+|\s+$/ig, '').replace(/[^\x00-\xff]/ig, 'xx').length / 2);
    }
};

/**
 * 统计输入字符串长度(用于评论回复最大字数计算)
 * @param  object obj  使用对象
 * @param  int len  最大长度
 * @param  bool show 提示class
 */
var checkNums = function(obj, len, show) {
    var str = $(obj).val();
    var _length = getLength(str);
    var surplus = len - _length;
    if (surplus < 0) {
        $('.' + show)
            .text(surplus)
            .css('color', 'red');
    } else {
        $('.' + show)
            .text(surplus)
            .css('color', '#59b6d7');
    }
}

/**
 * 文件上传 V2
 */
var fileUpload = {

    init: function(f, callback){
        var _this = this;
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            var image = new Image();
                image.src = data;
            _this.isUploaded(image, f, callback);
        };
        reader.readAsDataURL(f);
    },
    isUploaded:function(image, f, callback){
        var _this = this;
        var reader = new FileReader();
        reader.onload = function(e){
            var hash = md5(e.target.result);
            $.ajax({
                url: '/api/v2/files/uploaded/' + hash,
                type: 'GET',
                async: false,
                beforeSend: function(xhr) {xhr.setRequestHeader('Authorization', 'Bearer'+' '+TOKEN);},
                success: function(response) {
                    if(response.id > 0) callback(image, f, response.id);
                    console.log(response)
                },
                error: function(error){
                    error.status === 404 && _this.uploadFile(image, f, callback);
                    console.log(error.statusText)
                }
            });
        }
        reader.readAsArrayBuffer(f);
    },
    uploadFile: function(image, f, callback){
        var formDatas = new FormData();
        formDatas.append("file", f);
        // 上传文件
        $.ajax({
            url: '/api/v2/files',
            type: 'POST',
            data: formDatas,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(xhr) {xhr.setRequestHeader('Authorization', 'Bearer'+' '+TOKEN);},
            success: function(response) {
                if(response.id > 0) callback(image, f, response.id);
                console.log(response)
            },
            error: function(error){
                console.log(error.statusText)
            }
        });
    }
};

// 关注
var follow = function(status, user_id, target, callback) {
    if (status == 0) {
        var url = API + 'users/follow';
        $.ajax({
            url: url,
            type: 'POST',
            data: { user_id: user_id },
            beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', TOKEN) },
            success: function(response) {
                callback(target);
            }
        })
    } else {
        var url = API + 'users/unFollow';
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { user_id: user_id },
            beforeSend: function(xhr) { xhr.setRequestHeader('Authorization', TOKEN) },
            success: function(response) {
                callback(target);
            }
        })
    }
}

/**
 * 警告提示弹出框
 * @param  string   msg    提示文字
 * @param  int      status 0:失败 1:成功
 * @param  string   tourl  跳转链接
 */
var noticebox = function(msg, status, tourl) {
    tourl = tourl || '';
    var _this = $('.noticebox');
    if ($(document).scrollTop() > 62) {
        _this.css('top', '0px');
    } else {
        if (_this.hasClass('authnoticebox')) {
            _this.css('top', '82px');
        } else {
            _this.css('top', '62px');
        }
    }
    if (status == 0) {
        var html = '<div class="notice"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shibai-copy"></use></svg>' + msg + '</div>';
    } else {
        var html = '<div class="notice"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xuanzedui-copy"></use></svg>' + msg + '</div>';
    }
    _this.html(html);
    _this.slideDown(500);
    if (tourl == '') {
        setTimeout(function() {
            $('.noticebox').slideUp(200);
        }, 1000);
    } else {
        setTimeout(function() {
            noticebox_cb(tourl);
        }, 1500);
    }
}

var noticebox_cb = function(tourl) {
    window.location.href = tourl == 'refresh' ? window.location.href : tourl;
}

/**
 * 无数据提示dom
 * @param  string   selector 显示容器
 * @param  string   txt      提示文字
 * @return bool
 */
var no_data = function(selector, type, txt) {
    var image = type == 0 ? RESOURCE_URL + '/images/pic_default_content.png' : RESOURCE_URL + '/images/pic_default_people.png';
    var html = '<div class="no_data_div"><div class="no_data"><img src="' + image + '" /><p>' + txt + '</p></div></div>';
    $(selector).html(html);
}

/**
 * 退出登录提示框
 * @return bool
 */
var logout = function() {
    $('.p_cont').hide();
    var html = '<div class="modal-content exit_content">'
        + '<div class="modal-body exit_body">'
        + '<div class="exit_ts">提示</div>'
        + '<div class="exit_thinks">感谢您对ThinkSNS的信任，是否退出当前账号？</div>'
        + '<div data-dismiss="modal" class="exit_btn">'
        + '<a href="javascript:layer.closeAll()">取消</a>'
        + '</div>'
        + '<a href="' + request_url.logout + '"><span data-dismiss="modal" class="exit_btn exit_btn_bg">退出</span></a>'
        + '</div></div>' 
    layer.open({
      type: 1,
      title: false,
      closeBtn: 0,
      shadeClose: true,
      content: html
    });
}

//是否正在加载
var load = 0;
//layer弹窗
var ly = {
    /**
     * 操作成功显示API
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    success: function(message, reload, url, close) {
        reload = typeof(reload) == 'undefined' ? true : reload;
        close = typeof(close) == 'undefined' ? false : close;

        layer.msg(message, {
          icon: 1,
          time: 2000
        },function(index){
            if(close){
                layer.close(index);
            }
            if(reload){
                if(url == '' || typeof(url) == 'undefined') {
                    url = location.href;
                }
                location.href = url;
            }
        });
    },
    /**
     * 操作出错显示API
     * @param string message 信息内容
     * @param integer time 展示时间
     * @return void
     */
    error: function(message, reload, url, close) {
        reload = typeof(reload) == 'undefined' ? true : reload;
        close = typeof(close) == 'undefined' ? false : close;
        
        layer.msg(message, {
          icon: 2,
          time: 2000
        },function(index){
            if(close){
                layer.close(index);
            }
            if(reload){
                if(url == '' || typeof(url) == 'undefined') {
                    url = location.href;
                }
                location.href = url;
            }
        });     
    },
    load: function(requestUrl,title,width,height,type,requestData){
        if(load == 1) return false;
        layer.closeAll();
        load = 1;

        if(undefined != typeof(type)) {
            var ajaxType = type;
        }else{
            var ajaxType = "GET";
        }
        var obj = this;
        if(undefined == requestData) {
            var requestData = {};
        }
        layer.load(0, {shade: false});
        $.ajax({
            url: requestUrl,
            type: ajaxType,
            data: requestData,
            cache:false,
            dataType:'html',
            success:function(html){
                layer.closeAll();
                layer.open({
                    type: 1,
                    title: title,
                    area: [width,height],
                    shadeClose: true,
                    shade:0.5,
                    content: html
                });
                load = 0;
            }
        });
    },
    loadHtml: function(html,title,width,height){
        layer.closeAll();

        layer.open({
            type: 1,
            title: title,
            area: [width,height],
            shadeClose: true,
            shade:0.5,
            content: html
        });
    }
};


$(function() {
    // 个人中心展开
    $('.nav_right').hover(function() {
        $('.p_cont').toggle();
    })

    $('#gotop').click(function() {
        $(window).scrollTop(0);
    })

    $('body').click(function(e) {
        var target = $(e.target);
        if(!target.is('#menu_toggle') && target.parents('.p_cont').length == 0) {
           $('.p_cont').hide();
        }

        if(!target.is('.icon-gengduo-copy') && target.parents('.cen_more').length == 0) {
           $('.cen_more').hide();
        }
    });

    //  显示隐藏评论操作
    $(document).on("mouseover mouseout",".comment_con",function(event){
        if(event.type == "mouseover"){
            $(this).find("a").show();
        }else if(event.type == "mouseout"){
            $(this).find("a").hide();
        }
    });
})
