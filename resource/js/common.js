var defaultAvatar = RESOURCE_URL+'/images/avatar.png';
var loadHtml = "<div class='loading'><img src='" + RESOURCE_URL + "/images/loading.png' class='load'>加载中</div>";
var confirmTxt = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shibai-copy"></use></svg>';
var mark_time = MID + new Date().getTime();

// ajax 设置 headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        'Authorization': 'Bearer ' + TOKEN,
        'Accept': 'application/json'
    }
})

// layer 弹窗
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

// 文件上传
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


// 加载更多公共
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

// 存储对象创建
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

// url参数转换为对象
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

// 字符串长度计算
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

// 统计输入字符串长度(用于评论回复最大字数计算)
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

// 关注
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

// 消息提示
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

// 消息提示回调
var noticebox_cb = function(tourl) {
    window.location.href = tourl == 'refresh' ? window.location.href : tourl;
}

// 无数据提示dom
var no_data = function(selector, type, txt) {
    var image = type == 0 ? RESOURCE_URL + '/images/pic_default_content.png' : RESOURCE_URL + '/images/pic_default_people.png';
    var html = '<div class="no_data_div"><div class="no_data"><img src="' + image + '" /><p>' + txt + '</p></div></div>';
    $(selector).html(html);
}

// 退出登录提示
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

// 接口返回错误解析
var showError = function(obj) {
    for (var key in obj) 
    {
       noticebox(obj[key], 0);
       break;
    }
}

// 验证手机号
var checkPhone = function(string) {
    var pattern = /^1[34578]\d{9}$/;
    if (pattern.test(string)) {
        return true;
    }
    return false;
};

// 验证邮箱
var checkEmail = function(string) { 
    var pattern=/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/; 
    if (pattern.test(string)) {
        return true;
    }
    return false;
} 


// 设置Cookie
var setCookie = function(c_name,value,expiredays) {
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=c_name+ "=" +escape(value)+
    ((expiredays==null) ? "" : "; expires="+exdate.toGMTString())
}

// 获取cookie
var getCookie = function(c_name) {
    if (document.cookie.length>0)
    {
        var c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1)
        { 
            c_start=c_start + c_name.length+1 ;
            var c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) c_end=document.cookie.length;
            return unescape(document.cookie.substring(c_start,c_end));
        } 
   }
   return "";
}

$(function() {
    //获得用户时区与GMT时区的差值
    if (getCookie('customer_timezone') == '') {
        var exp = new Date();
        var gmtHours = -(exp.getTimezoneOffset()/60);
        setCookie('customer_timezone', gmtHours, 1);
    }

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

        if(!target.is('.icon-gengduo-copy') && target.parents('.options').length == 0) {
           $('.options_div').hide();
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
});