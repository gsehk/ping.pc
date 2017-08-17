var defaultAvatar = RESOURCE_URL+'/images/avatar.png';
var loadHtml = "<div class='loading'><img src='" + RESOURCE_URL + "/images/loading.png' class='load'>加载中</div>";
var confirmTxt = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shibai-copy"></use></svg>';
var mark_time = MID + new Date().getTime();


/**
 * layer
 * @type {Object}
 */
var load = 0;
var ly = {
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

/**
 * file upload v2
 * @type {Object}
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
                success: function(response) {
                    if(response.id > 0) callback(image, f, response.id);
                },
                error: function(error){
                    error.status === 404 && _this.uploadFile(image, f, callback);
                    showError(xhr.responseJSON);
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
            success: function(response) {
                if(response.id > 0) callback(image, f, response.id);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    }
};

/**
 * 微博初始化
 * $param object option 微博配置相关数据
 * @return void
 */
var scroll = {};
scroll.setting = {};
scroll.extra = {};
scroll.init = function(option) {
    this.extra = option.params || {};

    this.setting.container = option.container; // 容器ID
    this.setting.after = option.after || 0; // 最大ID
    this.setting.loading = option.loading || '.dy_cen'; //加载图位置
    this.setting.url = option.url;
    scroll.bindScroll();
    if ($(scroll.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $(scroll.setting.loading).after(loadHtml);
        scroll.loadMore();
    }
};


/**
 * 页面底部触发事件
 * @return void
 */
scroll.bindScroll = function() {
    $(window).bind('scroll resize', function() {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
            if ($(scroll.setting.container).length > 0) {
                $('.loading').remove();
                $(scroll.setting.loading).after(loadHtml);
                scroll.loadMore();
            }
        }
    });
};

/**
 * 获取加载的数据信息
 * @return void
 */
scroll.loadMore = function() {
    // 将能加载参数关闭
    scroll.setting.canload = false;
    scroll.setting.loadcount++;

    var postArgs = {};
    postArgs = $.extend(scroll.setting, scroll.extra);

    $.ajax({
        url: scroll.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error: function(xml) {},
        success: function(res) {
            if (res.after > 0) {
                scroll.setting.canload = true;
                // 修改加载ID
                scroll.setting.after = res.after;
                var html = res.data;
                if (scroll.setting.loadcount == 1) {
                    $(scroll.setting.container).html(html);
                    $('.loading').remove();
                } else {
                    $(scroll.setting.container).append(html);
                }
                $("img.lazy").lazyload({ effect: "fadeIn" });
            } else {
                scroll.setting.canload = false;
                if (scroll.setting.loadcount == 1) {
                    no_data(scroll.setting.container, 1, ' 暂无相关内容');
                    $('.loading').html('');
                } else {
                    $('.loading').html('暂无相关内容');
                }
            }
        }
    });
};

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
 * 关注
 * @param  int   status    提示文字
 * @param  int   user_id   用户id
 */
var follow = function(status, user_id, target, callback) {
    var url = API + '/user/followings/' + user_id;
    if (status == 0) {
        $.ajax({
            url: url,
            type: 'PUT',
            success: function(response) {
                callback(target);
            }
        })
    } else {
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

/**
 * 消息弹出框
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

/**
 * 消息弹出框回调
 * @param  string   tourl 跳转地址
 */
var noticebox_cb = function(tourl) {
    window.location.href = tourl == 'refresh' ? window.location.href : tourl;
}

/**
 * 无数据提示dom
 * @param  string   selector 显示容器
 * @param  string   txt      提示文字
 */
var no_data = function(selector, type, txt) {
    var image = type == 0 ? RESOURCE_URL + '/images/pic_default_content.png' : RESOURCE_URL + '/images/pic_default_people.png';
    var html = '<div class="no_data_div"><div class="no_data"><img src="' + image + '" /><p>' + txt + '</p></div></div>';
    $(selector).html(html);
}

/**
 * 退出登录提示框
 */
var logout = function() {
    $('.nav_menu').hide();
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


/**
 * 错误解析
 */
var showError = function(obj) {
    for (var key in obj) 
    {
       noticebox(obj[key], 0);
       break;
    }
}

$(function() {
    // 个人中心展开
    $('.nav_right').hover(function() {
        $('.nav_menu').toggle();
    })

    // 跳至顶部
    $('#gotop').click(function() {
        $(window).scrollTop(0);
    })

    // 弹出层点击其他地方关闭
    $('body').click(function(e) {
        var target = $(e.target);
        if(!target.is('#menu_toggle') && target.parents('.nav_menu').length == 0) {
           $('.nav_menu').hide();
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
