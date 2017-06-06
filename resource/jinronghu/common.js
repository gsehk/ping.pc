var initNums = 255;
var loadHtml = "<div class='loading'><img src='" + PUBLIC_URL + "/images/loading.png' class='load'>加载中</div>";
var confirmTxt = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shibai-copy"></use></svg>';
var request_url = {
    /* 登录 */
    login: '/passport/index',
    /* 注销 */
    logout: '/passport/logout',
    /* 获取文章列表 */
    get_news: '/information/getNewsList',
    /* 文章点赞 */
    digg_news: API + 'news/{news_id}/digg',
    /* 文章收藏  */
    collect_news: API + 'news/{news_id}/collection',
    /* 评论文章 */
    comment_news: API + 'news/{news_id}/comment',
    // 新闻评论
    get_comment: '/information/{news_id}/comments',

    /* 分享评论 */
    feed_comment: API + 'feeds/{feed_id}/comment',
    /* 删除分享评论  */
    del_feed_comment: API + 'feeds/{feed_id}/comment/{comment_id}',
    /* 删除文章评论 */
    del_news_comment: API + 'news/{news_id}/comment/{comment_id}',
    /* 删除分享 */
    delete_feed: API + 'feeds/{feed_id}',

    /*  举报分享  */
    denounce_feed: '/feed/{feed_id}/denounce',
    digg_feed: API + 'feeds/{feed_id}/digg',
    get_feed_commnet: '/home/{feed_id}/comments',
    collect_feed: API + 'feeds/{feed_id}/collection',
    get_user_feed: '/profile/users/{user_id}',
    get_user_news: '/profile/news/{user_id}',
    get_user_collect: '/profile/collection/{user_id}',
};

// Ajax 设置csrf Header
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        'Authorization': TOKEN,
    }
});

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

// 字符串长度 - 中文和全角符号为1；英文、数字和半角为0.5
var getLength = function(str, shortUrl) {
    if (true == shortUrl) {
        // 一个URL当作十个字长度计算
        return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
            .replace(/^\s+|\s+$/ig, '').replace(/[^\x00-\xff]/ig, 'xx').length / 2);
    } else {
        return Math.ceil(str.replace(/^\s+|\s+$/ig, '').replace(/[^\x00-\xff]/ig, 'xx').length / 2);
    }
};

var checkNums = function(obj, len, show) {
    var str = $(obj).val();
    var _length = getLength(str);
    var surplus = len - _length;
    if (surplus < 0) {
        $('.' + show).text(surplus).css('color', 'red');
        // noticebox('字数不能大于'+len, 0);
    } else {
        $('.' + show).text(surplus).css('color', '#59b6d7');
    }
}

// 文件上传
var fileUpload = function(f, callback) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        //加载图片获取图片真实宽度和高度
        var image = new Image();
        image.onload = function() {
            var width = image.width;
            var height = image.height;
            var size = f.size;
            doFileUpload(image, f, callback);
        };
        image.src = data;
    };
    reader.readAsDataURL(f);
};

var doFileUpload = function(image, f, callback) {
    var args = {
        width: image.width,
        height: image.height,
        mime_type: f.type,
        origin_filename: f.name,
        hash: CryptoJS.MD5(f.name).toString(),
    };
    // 创建存储任务
    $.ajax({
        url: API + 'storages/task',
        type: 'POST',
        async: false,
        data: args,
        beforeSend: function(xhr) {　　　　 xhr.setRequestHeader('Authorization', TOKEN);　　　 },
        success: function(res) {
            if (res.data.uri) {
                var formData = new FormData();
                formData.append("file", f);

                if (res.data.options) {
                    for (var i in res.data.options) {
                        formData.append(i, res.data.options[i]);
                    }
                }

                // 上传文件
                $.ajax({
                    url: res.data.uri,
                    type: res.data.method,
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(xhr) {　　　　 xhr.setRequestHeader('Authorization', res.data.headers.Authorization);　　　 },
                    success: function(data) {

                        // 上传通知 
                        $.ajax({
                            url: API + 'storages/task/' + res.data.storage_task_id,
                            type: 'PATCH',
                            async: false,
                            beforeSend: function(xhr) {　　　　 xhr.setRequestHeader('Authorization', res.data.headers.Authorization);　　　 },
                            success: function(response) {
                                callback(image, f, res.data.storage_task_id);
                            }
                        });
                    }
                });
            } else {
                callback(image, f, res.data.storage_task_id);
            }
        }
    });
};

// 关注
var follow = function(status, user_id, target, callback) {
    if (status == 0) {
        var url = API + 'users/follow';
        $.ajax({
            url: url,
            type: 'POST',
            data: { user_id: user_id },
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
            success: function(response) {
                callback(target);
            }
        })
    }
}

// 提示框
var noticebox = function(msg, status, tourl = '') {
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

var no_data = function(selector, type, txt) {
    // var image = type == 0 ? PUBLIC_URL + '/images/pic_default_content.png' : PUBLIC_URL + '/images/pic_default_people.png';
    var image = type == 0 ? PUBLIC_URL + '/jinronghu/pic_default_content.png' : PUBLIC_URL + '/jinronghu/pic_default_people.png';
    var html = '<div class="no_data_div"><div class="no_data"><img src="' + image + '" /><p>' + txt + '</p></div></div>';
    // $(selector).css('display', 'table');
    // $(selector).css('margin', '0 auto');
    $(selector).html(html);
}

var logout = function() {
    $('.p_cont').hide();

    var html = '<div class="modal-content exit_content">'
            + '<div class="modal-body exit_body">'
            + '<div class="exit_ts">提示</div>'
            // + '<div class="exit_thinks">感谢您对ThinkSNS的信任，是否退出当前账号？</div>'
            + '<div class="exit_thinks">感谢您对金融虎的信任，是否退出当前账号？</div>'
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

;
(function($) {
    //默认参数
    var defaluts = {
        select: "select",
        select_text: "select_text",
        select_ul: "select_ul"
    };
    $.fn.extend({
        "select": function(options) {
            var opts = $.extend({}, defaluts, options);
            return this.each(function() {
                var $this = $(this);
                //模拟下拉列表
                if ($this.data("value") !== undefined && $this.data("value") !== '') {
                    $this.val($this.data("value"));
                }
                var _html = [];
                _html.push("<div class=\"" + $this.attr('class') + "\">");
                _html.push("<div class=\"" + opts.select_text + "\">" + $this.find(":selected").text() + "</div>");
                _html.push("<ul class=\"" + opts.select_ul + "\">");
                $this.children("option").each(function() {
                    var option = $(this);
                    if ($this.data("value") == option.val()) {
                        _html.push("<li class=\"cur\" data-value=\"" + option.val() + "\">" + option.text() + "</li>");
                    } else {
                        _html.push("<li data-value=\"" + option.val() + "\">" + option.text() + "</li>");
                    }
                });
                _html.push("</ul>");
                _html.push("</div>");
                var select = $(_html.join(""));
                var select_text = select.find("." + opts.select_text);
                var select_ul = select.find("." + opts.select_ul);
                $this.after(select);
                $this.hide();
                //下拉列表操作
                select.click(function(event) {
                    $(this).find("." + opts.select_ul).slideToggle().end().siblings("div." + opts.select).find("." + opts.select_ul).slideUp();
                    event.stopPropagation();
                });
                $("body").click(function() {
                    select_ul.slideUp();
                });
                select_ul.on("click", "li", function() {
                    var li = $(this);
                    var val = li.addClass("cur").siblings("li").removeClass("cur").end().data("value").toString();
                    if (val !== $this.val()) {
                        select_text.text(li.text());
                        $this.val(val);
                        $this.attr("data-value", val);
                    }
                });
            });
        }
    });
})(jQuery);

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
    success: function(message, url, reload, close) {
        reload = typeof(reload) == 'undefined' ? true : reload;
        close = typeof(close) == 'undefined' ? false : close;

        layer.msg(message, {
          icon: 1,
          time: 2000
        },function(){
            if(close){
                layer.closeAll();
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
    error: function(message, url, reload, close) {
        reload = typeof(reload) == 'undefined' ? true : reload;
        close = typeof(close) == 'undefined' ? false : close;
        
        layer.msg(message, {
          icon: 2,
          time: 2000
        },function(){
            if(close){
                layer.closeAll();
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
    $('#menu_toggle').click(function() {
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
