var loadHtml = "<div class='loading'><img src='" + TS.RESOURCE_URL + "/images/three-dots.svg' class='load'></div>";
var clickHtml = "<div class='click_loading'><a href='javascript:;'>加载更多<svg class='icon mcolor' aria-hidden='true'><use xlink:href='#icon-icon07'></use></svg></a></div>";
var confirmTxt = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-warning"></use></svg> ';
var initNums = 255;

axios.defaults.baseURL = TS.SITE_URL;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

// 获取浏览器信息
var browser = {
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {    //移动终端浏览器版本信息
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
        };
    }(),
    language:(navigator.browserLanguage || navigator.language).toLowerCase()
}

// 本地存储
var storeLocal = {
    set: function(key, value){
        window.localStorage.setItem(key, JSON.stringify(value));
    },
    get: function(key){
        var data = window.localStorage.getItem(key);
        if (!data) {
            return false;
        } else {
            return JSON.parse(data);
        }
    },
    remove: function(key){
        window.localStorage.removeItem(key);
    },
    clear: function(){
        window.localStorage.clear();
    }
}

// 数组去重
var unique = function(array) {
    var r = [];
    for(var i = 0, l = array.length; i < l; i++) {
        for(var j = i + 1; j < l; j++)
            if (array[i] === array[j]) j = ++i;
            r.push(array[i]);
    }
    return r;
}

// layer 弹窗
var load = 0;
var ly = {
    close: function () {
        layer.closeAll();
    },
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
        var obj = this;
        var ajaxType = "GET";
        if(load == 1) return false;
        layer.closeAll();
        load = 1;
        if(undefined != type) {
            ajaxType = type;
        }
        if(undefined == requestData) {
            var requestData = {};
        }
        axios({
          method:ajaxType,
          url:requestUrl,
          data:requestData,
        })
          .then(function(response) {
            layer.closeAll();
            layer.open({
                type: 1,
                title: title,
                area: [width,height],
                shadeClose: true,
                shade:0.5,
                scrollbar: false,
                content: response.data
            });
            load = 0;
        });
    },
    loadHtml: function(html,title,width,height){
        layer.closeAll();
        layer.open({
            type: 1,
            title: title,
            area: [width,height],
            shadeClose: true,
            shade: 0.5,
            scrollbar: false,
            content: html
        });
    },
    confirm: function (html, confirmBtn, cancelBtn, callback) {
        confirmBtn = confirmBtn || '确认';
        cancelBtn = cancelBtn || '取消';
        layer.confirm(html, {
            btn: [confirmBtn, cancelBtn],
            title: '',
            shadeClose: true,
            shade:0.5,
            scrollbar: false
        }, function(){
            callback();
        }, function(){
            layer.closeAll();
        });
    },
    alert: function (html, btn, callback) {
        btn = btn || '知道了';
        callback = callback || false;
        layer.alert(html, {
            btn: btn,
            title: '',
            scrollbar: false,
            area: ['auto', 'auto']
        }, function(){
            layer.closeAll();
            return callback ? callback() : false;
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
            axios.get('/api/v2/files/uploaded/'+hash)
              .then(function (response) {
                if(response.data.id > 0) callback(image, f, response.data.id);
              })
              .catch(function (error) {
                error.response.status === 404 && _this.uploadFile(image, f, callback);
              });
        }
        reader.readAsArrayBuffer(f);
    },
    uploadFile: function(image, f, callback){
        var formDatas = new FormData();
            formDatas.append("file", f);
        axios.post('/api/v2/files', formDatas)
          .then(function (response) {
            if(response.data.id > 0) callback(image, f, response.data.id);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }
};


// 加载更多公共
var scroll = {};
scroll.setting = {};
scroll.params = {};
scroll.init = function(option) {
    this.params = option.params || {};
    this.setting.container = option.container; // 容器ID
    this.setting.paramtype = option.paramtype || 0; // 参数类型，0为after，1为offset
    this.setting.loadtype = option.loadtype || 0; // 加载方式，0为一直加载更多，1为3次以后点击加载，2为点击加载
    this.setting.loading = option.loading; //加载图位置
    this.setting.loadcount = option.loadcount || 0; // 加载次数
    this.setting.canload = option.canload || true; // 是否能加载
    this.setting.url = option.url;
    this.setting.nodata = option.nodata || 0; // 0显示，1不显示
    this.setting.callback = option.callback || null;

    scroll.bindScroll();

    if ($(scroll.setting.container).length > 0 && this.setting.canload) {
        $('.loading').remove();
        $('.click_loading').remove();
        $(scroll.setting.loading).after(loadHtml);
        scroll.loadMore();
    }
};

scroll.bindScroll = function() {
    $(window).bind('scroll resize', function() {
        if (scroll.setting.canload){
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                if ($(scroll.setting.container).length > 0) {
                    // 一直加载更多
                    if (scroll.setting.loadtype == 0) {
                        $('.loading').remove();
                        $(scroll.setting.loading).after(loadHtml);
                        scroll.loadMore();
                    }

                    // 加载三次点击加载更多
                    if (scroll.setting.loadtype == 1) {
                        if ((scroll.setting.loadcount % 3) != 0) {
                            $('.loading').remove();
                            $(scroll.setting.loading).after(loadHtml);
                            scroll.loadMore();
                        } else {
                            if ($(scroll.setting.loading).siblings('.click_loading').length == 0) {
                                $(scroll.setting.loading).after(clickHtml);
                            }
                        }
                    }
                }
            }
        }
    });
};

scroll.loadMore = function() {
    // 将能加载参数关闭
    scroll.setting.canload = false;
    scroll.setting.loadcount++;
    scroll.params.loadcount = scroll.setting.loadcount;
    axios.get(scroll.setting.url, {params:scroll.params})
      .then(function (response) {
        var res = response.data;
        if (res.data != '') {
            scroll.setting.canload = true;

            // 两种不同的加载方式
            if (scroll.setting.paramtype == 0) {
                scroll.params.after = res.after;
            } else {
                scroll.params.offset = scroll.setting.loadcount * scroll.params.limit;
            }

            var html = res.data;
            if (scroll.setting.loadcount == 1) {
                $(scroll.setting.container).html(html);
            } else {
                $(scroll.setting.container).append(html);
            }
            $('.loading').remove();

            // 点击加载更多
            if (scroll.setting.loadtype == 2) {
                res.count = res.count ? res.count : 0;
                if (scroll.params.limit <= res.count) {
                    $(scroll.setting.loading).after(clickHtml);
                }
            }

            $("img.lazy").lazyload({ effect: "fadeIn" });
        } else {
            scroll.setting.canload = false;
            if (scroll.setting.loadcount == 1 && scroll.setting.nodata == 0) {
                no_data(scroll.setting.container, 1, ' 暂无相关内容');
                $('.loading').html('');
            } else {
                $('.loading').html('没有更多了');
            }
        }
        // 若隐藏则显示
        if ($(scroll.setting.container).css('display') == 'none') {
            $(scroll.setting.container).show();
        }

        if (scroll.setting.callback && typeof(scroll.setting.callback) == 'function') {
            scroll.setting.callback();
        }
      })
      .catch(function (error) {
        showError(error.response.data);
      });
};

scroll.clickMore = function(obj) {
    // 将能加载参数关闭
    scroll.setting.canload = false;
    scroll.setting.loadcount++;
    $(obj).parent().html("<img src='"+TS.RESOURCE_URL+"/images/three-dots.svg' class='load'>");
    axios.get(scroll.setting.url, {params:scroll.params})
      .then(function (response) {
        var res = response.data;
        if (res.data != '') {
            scroll.setting.canload = true;

            // 两种不同的加载方式
            if (scroll.setting.paramtype == 0) {
                scroll.params.after = res.after;
            } else {
                scroll.params.offset = scroll.setting.loadcount * scroll.params.limit;
            }

            var html = res.data;
            $(scroll.setting.container).append(html);
            $('.click_loading').remove();

            // 点击加载更多
            if (scroll.setting.loadtype == 2) {
                res.count = res.count ? res.count : 0;
                if (scroll.params.limit <= res.count) {
                    $(scroll.setting.loading).after(clickHtml);
                }
            }

            $("img.lazy").lazyload({ effect: "fadeIn" });
        } else {
            scroll.setting.canload = false;
            $('.click_loading').html('没有更多了');
        }
      })
      .catch(function (error) {
        showError(error.response.data);
      });
}


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
    var surplus = len - str.length;
    if (surplus < 0) {
        $(obj).parent().find('.' + show)
            .text(surplus)
            .css('color', 'red');
    } else {
        $(obj).parent().find('.' + show)
            .text(surplus)
            .css('color', '#59b6d7');
    }
};

// 关注
var follow = function(status, user_id, target, callback) {
    checkLogin();

    var url = '/api/v2/user/followings/'+user_id;
    if (status == 0) {
        axios.put(url)
          .then(function (response) {
            callback(target);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    } else {
        axios.delete(url, {params: {user_id: user_id}})
          .then(function (response) {
            callback(target);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }
}

// 话题
var topic = function(status, topic_id, callback) {
    checkLogin();
    var url = '/api/v2/user/question-topics/'+topic_id;
    if (status == 0) {
        axios.put(url)
          .then(function (response) {
            callback();
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    } else {
        axios.delete(url)
          .then(function (response) {
            callback();
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }
}

// 弹窗消息提示
var lyNotice = function(msg) {
    var _this = $('.layui-layer-content');
    var lr = $('.ly-error');

    if(typeof lr =='undefined' || lr.length < 1){
        _this.append('<span class="ly-error"></span>');
        lr = $('.ly-error');
    }
    lr.text(msg);

    return false;
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
        var html = '<div class="notice"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-warning"></use></svg> ' + msg + '</div>';
    } else {
        var html = '<div class="notice"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-choosed"></use></svg> ' + msg + '</div>';
    }
    _this.html(html);
    _this.slideDown(200);
    if (tourl == '') {
        setTimeout(function() {
            $('.noticebox').slideUp(200);
        }, 1500);
    } else {
        setTimeout(function() {
            noticebox_cb(tourl);
        }, 1500);
    }
}

// 消息提示回调
var noticebox_cb = function(tourl) {
    window.location.href = tourl == 'refresh' ? window.location.href : TS.SITE_URL + tourl;
}

// 无数据提示dom
var no_data = function(selector, type, txt) {
    var image = type == 0 ? TS.RESOURCE_URL + '/images/pic_default_content.png' : TS.RESOURCE_URL + '/images/pic_default_people.png';
    var html = '<div class="no_data_div"><div class="no_data"><img src="' + image + '" /><p>' + txt + '</p></div></div>';
    $(selector).html(html);
}

// 退出登录提示
$('#action-logout').on('click', function(event) {
    event.preventDefault();
    $.removeAllCookie();
    $('.nav_menu').hide();
    var action = formatConfirm('提示', '是否确认退出当前账号？');
    var url = this.href;
    ly.confirm(action, '', '', function() {
        window.location.href = url;
    });
});

// 接口返回错误解析
var showError = function(message, defaultMessage) {
    defaultMessage = defaultMessage || '出错了';
    if (message.errors !== undefined && message.errors !== null) {
        var message = message.errors;
        for (var key in message) {
            if (Array.isArray(message[key])) {

                noticebox(message[key], 0);
                return;
            }
        }
        noticebox(defaultMessage, 0);
        return;
    }
    if (message.message !== undefined && message.message !== null) {
        noticebox(message.message || defaultMessage, 0);
        return;
    }
    for (var key in message) {
        if (Array.isArray(message[key])) {
            noticebox(message[key], 0);
            return;
        }
    }
    noticebox(defaultMessage, 0);
    return;
};

// ly.confirm 弹窗接口返回错误解析
var lyShowError = function(message, defaultMessage) {
    defaultMessage = defaultMessage || '操作失败';
    if (message.errors && message.errors !== null) {
        var message = message.errors;
        for (var key in message) {
            if (Array.isArray(message[key])) {

                lyNotice(message[key]);
                return;
            }
        }
        lyNotice(defaultMessage);
        return;
    }
    if (message.message && message.message !== null) {
        var message = message.message;
        for (var key in message) {
            // if (Array.isArray(message[key])) {
            lyNotice(message[key]);
            return;
            // }
        }
        lyNotice(defaultMessage);
        return;
    }
    for (var key in message) {
        if (Array.isArray(message[key])) {
            lyNotice(message[key]);
            return;
        }
    }
    lyNotice(defaultMessage);
    return;
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
    if (string.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
        return true;
    }
    return false;
}

// 签到
var checkIn = function(is_check, nums) {
    var url = '/api/v2/user/checkin';
    if (!is_check) {
        axios.put(url)
          .then(function (response) {
            noticebox('签到成功', 1);
            $('#checkin').addClass('checked_div');
            var html = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-checkin"></use></svg>已签到<span>连续签到<font class="colnum">'+(nums+1)+'</font>天</span>';
            $('#checkin').html(html);
            $('#checkin').removeAttr('onclick');
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    }
}

// 打赏
var rewarded = {
    show: function(id, type) {
        checkLogin();
        var html = '<div class="reward_box">'
                    + '<p class="confirm_title">打赏</p>'
                    + '<div class="reward_text">选择打赏金额</div>'
                    + '<div class="reward_spans">';
                    $.each(TS.BOOT.site.reward.amounts.split(','), function (index, value) {
                        if (value > 0) {
                            html += '<span num="' + value / TS.BOOT['wallet:ratio'] + '">' + value + '</span>';
                        }
                    });
                    html += '</div>'
                    + '<div class="reward_input">'
                        + '<input min="1" oninput="value=moneyLimit(value)" onkeydown="if ( !isNumber(event.keyCode) ) return false; " type="number" placeholder="自定义打赏金额，必须为整数">'
                    + '</div>'
                + '</div>';

        ly.confirm(html, '打赏', '', function(){
            var num = $('.reward_spans .current').length > 0 ? $('.reward_spans .current').attr('num') : '';
            var amount = $('.reward_input input').val() / TS.BOOT['wallet:ratio'];
            var url = '/api/v2/feeds/'+id+'/rewards';

            if (!num && !amount) {
                return false;
            }
            if (type == 'news') {
                url = '/api/v2/news/'+id+'/rewards';
            }
            if (type == 'answer') {
                url = '/api/v2/question-answers/'+id+'/rewarders';
            }
            if (type == 'user') {
                url = '/api/v2/user/'+id+'/rewards';
            }
            if (type == 'group-posts') {
                url = '/api/v2/plus-group/group-posts/'+id+'/rewards';
            }
            axios.post(url, {amount: num ? num : amount})
              .then(function (response) {
                ly.close();
                noticebox(response.data.message, 1, 'refresh');
              })
              .catch(function (error) {
                lyShowError(error.response.data);
              });
        });
        $('.reward-sum label').on('click', function(){
            $('.reward-sum label').removeClass('active');
            $(this).addClass('active');
        })
    },
    list: function(id, type){
        var reward_url = TS.SITE_URL + '/reward/view?type='+type+'&post_id='+id;
        ly.load(reward_url, '', '340px');
    }
}

var getMaps = function(callback){
    var html = '<div id="container" class="map" tabindex="0"></div>'+
                    '<div id="pickerBox">'+
                    '<input id="pickerInput" placeholder="输入关键字选取地点" /><button id="getpoi">确定</button>'+
                    '<div id="poiInfo"></div>'+
                '</div>';
    ly.loadHtml(html, '', 600, 500);
    var map = new AMap.Map('container', { zoom: 12 });
    AMapUI.loadUI(['misc/PoiPicker'], function(PoiPicker) {
        var poiPicker = new PoiPicker({
            // city:'北京',
            input: 'pickerInput'
        });
        poiPickerReady(poiPicker);
    });
    function poiPickerReady(poiPicker) {
        window.poiPicker = poiPicker;
        var marker = new AMap.Marker();
        var infoWindow = new AMap.InfoWindow({
            offset: new AMap.Pixel(0, -20)
        });
        //选取了某个POI
        poiPicker.on('poiPicked', function(poiResult) {
            var source = poiResult.source,
                poi = poiResult.item,
                info = {
                    source: source,
                    id: poi.id,
                    name: poi.name,
                    location: poi.location.toString(),
                    address: poi.address
                };
            marker.setMap(map);
            infoWindow.setMap(map);
            marker.setPosition(poi.location);
            infoWindow.setPosition(poi.location);
            // infoWindow.setContent('POI信息: <pre>' + JSON.stringify(info, null, 2) + '</pre>');
            infoWindow.open(map, marker.getPosition());
            map.setCenter(marker.getPosition());
            $('#pickerInput').val(poi.name);
            $('#getpoi').on('click', function(){
                ly.close();
                callback(poi);
            });
        });
        poiPicker.onCityReady(function() {
            poiPicker.suggest('php');
        });
    }
}
// 评论
var comment = {
    support: {
        count: 0,
        row_id: 0,
        to_uid: 0,
        to_uname: '',
        position: 0,
        editor: {},
        button: {},
        wordcount: 255,
        top: true
    },
    // 初始化回复操作
    reply: function(id, source_id, name) {
        this.support.to_uid = id;
        this.support.to_uname = name;
        this.support.row_id = source_id;
        this.support.editor = $('#J-editor'+this.support.row_id);
        this.support.editor.val('回复@'+this.support.to_uname+'：');
        this.support.editor.focus();
    },
    publish: function(url, callback) {
        checkLogin()
        var _this = this;
        if (_this.lockStatus == 1) {
            noticebox('请勿重复提交', 0);
            return;
        }
        var formData = { body: this.support.editor.val() };
        if (!formData.body) {
            noticebox('评论内容不能为空', 0); return;
        }

        // 保留原始回复内容
        var original_body = formData.body;
        // 去除回复@
        if (this.support.to_uid > 0) {
            if (formData.body == '回复@'+this.support.to_uname+'：') {
                noticebox('回复内容不能为空', 0); return;
            }
            formData.body = formData.body.split('：')[1];
            formData.reply_user = this.support.to_uid;
        }
        if (formData.body.length > 255) {
            noticebox('内容超出长度限制', 0); return;
        }

        this.support.button.text('评论中..');
        _this.lockStatus = 1;
        axios.post(url, formData)
          .then(function (response) {
            _this.support.button.text('评论');
            _this.support.editor.val('');
            _this.support.to_uid = 0;
            var res = response.data;
            var info = {
                id: res.comment.id,
                commentable_id: _this.support.row_id,
            };
            if (_this.support.position) {
                var html = '<p class="comment_con" id="comment'+res.comment.id+'">';
                    html +=     '<span class="tcolor">' + TS.USER.name + '：</span>' + original_body + '';
                    if (_this.support.top)
                    html +=     '<a class="comment_del mouse" onclick="comment.pinneds(\'' + res.comment.commentable_type + '\', ' + res.comment.commentable_id + ', ' + res.comment.id + ')">申请置顶</a>'
                    html +=     '<a class="comment_del mouse" onclick="comment.delete(\'' + res.comment.commentable_type + '\', ' + res.comment.commentable_id + ', ' + res.comment.id + ')">删除</a>'
                    html += '</p>';
            } else {
                var html  = '<div class="comment_item" id="comment'+res.comment.id+'">';
                    html += '    <dl class="clearfix">';
                    html += '        <dt>';
                    html += '            <img src="' + getAvatar(TS.USER, 50) + '" width="50">';
                    html += '        </dt>';
                    html += '        <dd>';
                    html += '            <span class="reply_name">' + TS.USER.name + '</span>';
                    html += '            <div class="reply_tool feed_datas">';
                    html += '                <span class="reply_time">刚刚</span>';
                    html += '                <span class="reply_action options" onclick="options(this)"><svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg></span>';
                    html += '                <div class="options_div">'
                    html += '                    <div class="triangle"></div>'
                    html += '                    <ul>';
                if (_this.support.top) {
                    html += '                        <li>'
                    html += '                            <a href="javascript:;" onclick="comment.pinneds(\'' + res.comment.commentable_type + '\', ' + res.comment.commentable_id + ', ' + res.comment.id + ');">'
                    html += '                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶'
                    html += '                            </a>'
                    html += '                        </li>';
                }
                    html += '                        <li>'
                    html += '                            <a href="javascript:;" onclick="comment.delete(\'' + res.comment.commentable_type + '\', ' + res.comment.commentable_id + ', ' + res.comment.id + ');">'
                    html += '                                <svg class="icon"><use xlink:href="#icon-delete"></use></svg>删除'
                    html += '                            </a>'
                    html += '                        </li>'
                    html += '                    </ul>'
                    html += '                </div>'
                    html += '            </div>';
                    html += '            <div class="reply_body">'+original_body+'</div>';
                    html += '        </dd>';
                    html += '    </dl>';
                    html += '</div>';
            }
            $('#J-commentbox'+_this.support.row_id).find('.no_data_div').remove();/*第一次评论去掉缺省图*/
            $('#J-commentbox'+_this.support.row_id).prepend(html);
            _this.lockStatus = 0;
            callback(res);
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.support.button.text('评论');
            _this.lockStatus =0;
          });
    },
    delete: function(type, source_id, id) {
        var url = '';
        var _this = this;
        if (_this.lockStatus == 1) {
            noticebox('请勿重复提交', 0);
            return;
        }
        switch (type) {
            case 'feeds':
                url = '/api/v2/feeds/' + source_id + '/comments/' + id;
                break;
            case 'news':
                url = '/api/v2/news/' + source_id + '/comments/' + id;
                break;
            case 'group-posts':
                var group_id = window.location.pathname.split("/")[2];
                url = '/api/v2/plus-group/group-posts/' + source_id + '/comments/' + id;
                break;
            case 'question-answers':
                url = '/api/v2/question-answers/' + source_id + '/comments/' + id;
                break;
            case 'questions':
                url = '/api/v2/questions/' + source_id + '/comments/' + id;
                break;
        }
        _this.lockStatus = 1;
        axios.delete(url)
          .then(function (response) {
            $('#comment' + id).fadeOut();
            $('.cs' + source_id).text(parseInt($('.cs' + source_id).text())-1);
            _this.lockStatus = 0;
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.lockStatus =0;
          });
    },
    pinneds: function (type, source_id, id){
        var url = '';
        if (type == 'feeds') {
            url = '/api/v2/feeds/' + source_id + '/comments/' + id + '/pinneds';
            pinneds(url);
        }
        if (type == 'news') {
            url = '/api/v2/news/' + source_id + '/comments/' + id + '/pinneds';
            pinneds(url);
        }
        if (type == 'group-posts') {
            url = '/api/v2/plus-group/pinned/comments/'+ id;
            pinneds(url);
        }
    }
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
        axios.post(_this.res.link)
          .then(function (response) {
            _this.num ++;
            _this.lockStatus = 0;
            _this.box.attr('rel', _this.num);
            _this.box.attr('status', 1);
            _this.box.find('a').addClass('act');
            _this.box.find('font').text(_this.num);
            if (_this.type) {
                _this.box.find('svg').html('<use xlink:href="#icon-likered"></use>');
            } else {
                _this.box.find('svg').html('<use xlink:href="#icon-like"></use>');
            }
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.lockStatus =0;
          });
    },
    unlike: function(feed_id, page) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        axios.delete(_this.res.unlink)
          .then(function (response) {
            _this.num --;
            _this.lockStatus = 0;
            _this.box.attr('rel', _this.num);
            _this.box.attr('status', 0);
            _this.box.find('a').removeClass('act');
            _this.box.find('font').text(_this.num);
            _this.box.find('svg').html('<use xlink:href="#icon-like"></use>');
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.lockStatus =0;
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
                res.link = '/api/v2/news/' + this.row_id + '/likes';
                res.unlink = res.link;
            break;
            case 'group':
                var group_id = window.location.pathname.split("/")[2];
                res.link = '/api/v2/plus-group/group-posts/' + this.row_id + '/likes';
                res.unlink = res.link;
            break;
            case 'question':
                res.link = '/api/v2/question-answers/' + this.row_id + '/likes';
                res.unlink = res.link;
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
        axios.post(_this.res.link)
          .then(function (response) {
            _this.num ++;
            _this.lockStatus = 0;
            _this.box.attr('rel', _this.num);
            _this.box.attr('status', 1);
            _this.box.find('a').addClass('act');
            _this.box.find('font').text(_this.num);
            _this.box.find('span').text('已收藏');
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.lockStatus =0;
          });
    },
    uncollect: function(feed_id, page) {
        var _this = this;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;
        axios.delete(_this.res.unlink)
          .then(function (response) {
            _this.num --;
            _this.lockStatus = 0;
            _this.box.attr('rel', _this.num);
            _this.box.attr('status', 0);
            _this.box.find('a').removeClass('act');
            _this.box.find('font').text(_this.num);
            _this.box.find('span').text('收藏');
          })
          .catch(function (error) {
            showError(error.response.data);
            _this.lockStatus =0;
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
                res.link = '/api/v2/news/' + this.row_id + '/collections';
                res.unlink = res.link;
            break;
            case 'group':
                var group_id = window.location.pathname.split("/")[2];
                res.link = '/api/v2/plus-group/group-posts/' + this.row_id + '/collections';
                res.unlink = '/api/v2/plus-group/group-posts/' + this.row_id + '/uncollect';
            break;
            case 'question':
                res.link = '/api/v2/user/question-answer/collections/' + this.row_id;
                res.unlink = res.link;
            break;
        }

        return res;
    }
};

// 申请置顶
var pinneds = function (url, type) {
    var html = '<div class="pinned_box">'
            + '<p class="confirm_title">申请置顶</p>'
            + '<div class="pinned_text">选择置顶天数</div>'
            + '<div class="pinned_spans">'
                + '<span days="1">1d</span>'
                + '<span days="5">5d</span>'
                + '<span days="10">10d</span>'
            + '</div>'
            + '<div class="pinned_text">设置置顶金额</div>'
            + '<div class="pinned_input">'
                + '<input min="1" oninput="value=moneyLimit(value)" type="number" placeholder="自定义置顶金额，必须为整数">'
            + '</div>'
            + '<div class="pinned_text">当前平均置顶金额为' + TS.BOOT.site.gold_name.name + '200/天，钱包余额为' + TS.USER.wallet.balance * TS.BOOT['wallet:ratio'] + '</div>'
            + '<div class="pinned_text">需要支付总金额：</div>'
            + '<div class="pinned_total"><span>0</span></div>'
        + '</div>';
    if (type == 'pinned') {
        html = '<div class="pinned_box">'
            + '<p class="confirm_title">置顶帖子</p>'
            + '<div class="pinned_text">设置帖子置顶天数</div>'
            + '<div class="pinned_input">'
                + '<input min="1" oninput="value=moneyLimit(value)" type="number" placeholder="设置范围为1~30天">'
            + '</div>'
        + '</div>';
    }

    ly.confirm(html, '', '', function(){
        var data = {};
        if (type != 'pinned') {
            data.day = $('.pinned_spans .current').length > 0 ? $('.pinned_spans .current').attr('days') : '';
            data.amount = $('.pinned_input input').val() / TS.BOOT['wallet:ratio'] * data.day;
            if (!data.day) {
                lyNotice('请选择置顶天数');return;
            }
            if (!data.amount) {
                lyNotice('请输入置顶金额');return;
            }
        } else {
            data.day = $('.pinned_input input').val();
            if (!data.day) {
                lyNotice('请输入置顶天数');return;
            }
        }
        axios.post(url, data)
          .then(function (response) {
            layer.closeAll();
            noticebox(response.data.message, 1);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    });
};

// 举报
var reported = function (url) {
    checkLogin();
    var html = '<div class="pinned_box mr20 ml20 mt20">'
                + '<p class="confirm_title">举报</p>'
                + '<a class="ucolor">举报理由</a>'
                + '<div class="pinned_input">'
                    + '<textarea id="report-ct" rows="4" cols="30" placeholder="请输入举报理由，不超过190字"></textarea>'
                + '</div>'
            + '</div>';
    ly.confirm(html, '', '', function(){
        var reason = $('#report-ct').val();
        if (!reason) {
            lyNotice('请输入举报理由');
            return false;
        }
        if (reason.length > 190) {
            lyNotice('举报理由不能大于190个字');
            return false;
        }
        axios.post(url, {reason: reason, content: reason})
          .then(function (response) {
            layer.closeAll();
            noticebox(response.data.message, 1);
          })
          .catch(function (error) {
            showError(error.response.data);
          });
    });
};

// 更多操作
var options = function(obj) {
    if ($(obj).next('.options_div').css('display') == 'none') {
        $('.options_div').hide();
        $(obj).next('.options_div').show();
    } else {
        $(obj).next('.options_div').hide();
    }
}

// 存入搜索记录
var setHistory = function(str) {
    if (localStorage.history) {
        hisArr = JSON.parse(localStorage.history);
        if ($.inArray(str, hisArr) == -1) {
            hisArr.push(str);
        }
    } else {
        hisArr = new Array();
        hisArr.push(str);
    }
    var hisStr = JSON.stringify(hisArr);
    localStorage.history = hisStr;
}

// 获取历史记录
var getHistory = function() {
    var hisArr = new Array();
    if (localStorage.history) {
        str = localStorage.history;
        //重新转换为对象
        hisArr = JSON.parse(str);
    }
    return hisArr;
}

// 删除记录
var delHistory = function(str) {
    if (str == 'all') {
        localStorage.history = '';
        $('.history').hide();
    } else {
        hisArr = JSON.parse(localStorage.history);
        hisArr.splice($.inArray(str, hisArr), 1);

        var hisStr = JSON.stringify(hisArr);
        localStorage.history = hisStr;
    }
}

//验证登录
var checkLogin = function() {
    if (TS.MID == 0) {
        window.location.href = TS.SITE_URL + '/auth/login';
        throw new Error("请登录");
    }
}

// 组装确认提示
var formatConfirm = function(title, text) {
    var html = '<div class="confirm_body">'
                + '<p class="confirm_title">' + title + '</p>'
                + '<div class="confirm_text">' + text + '</div>'
                + '</div>';
    return html;
}

// 获取参数
var getParams = function(url, key) {
    var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
    var r = url.match(reg);
    if(r!=null) return unescape(r[2]);
    return null;
}

// 置顶等限制金额
var moneyLimit = function(value) {
    // 最多八位
    if(value.length > 8) {
        value = value.slice(0,8);
    }

    // 最小值为1
    if(value <= 0) {
        value = '';
    }
    return value;
}

// 仅能输入数字
function isNumber(keyCode) {
    $('.ly-error').remove();
    // 数字
    if (keyCode >= 48 && keyCode <= 57 )
        return true;
    // 小数字键盘
    if (keyCode >= 96 && keyCode <= 105)
        return true;
    // Backspace, del, 左右方向键
    if (keyCode == 8 || keyCode == 46 || keyCode == 37 || keyCode == 39)
        return true;

    lyNotice('打赏金额必须为整数');

    return false;
}

// 第三方分享
var thirdShare = function(type, url, title, pic, obj) {
    type = type || 1;
    url = url || TS.SITE_URL;
    title = title || '快来看看吧';
    pic = pic || '';
    var tourl = '';
    switch(type) {
        case 1: // 微博
            tourl = 'http://service.weibo.com/share/share.php?url=';
            tourl += encodeURIComponent(url);
            tourl += '&title=';
            tourl += title;
            if (pic != '') {
                tourl += '&pic=';
                tourl += pic;
            }
            tourl += '&searchPic=true';
            window.open(tourl);
          break;
        case 2: // QQ
            tourl = 'http://connect.qq.com/widget/shareqq/index.html?url=';
            tourl += encodeURIComponent(url);
            tourl += '&title=';
            tourl += title;
            tourl += '&desc=';
            tourl += title;
            if (pic != '') {
                tourl += '&pics=';
                tourl += pic;
            }
            window.open(tourl);
          break;
        case 3: // 微信
            $('.weixin_qrcode').html('');
            $('.weixin_qrcode').qrcode({
                width: 200,
                height:200,
                text: url //任意内容
            });
            ly.loadHtml($('.weixin_qrcode'), '');
          break;
    }
}

/**
 * 获取用户信息
 * @param  int uids 用户id
 * @return user
 */
var getUserInfo = function(uids) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            'Authorization': 'Bearer ' + TS.TOKEN,
            'Accept': 'application/json'
        }
    })
    var user = [];
    var type = typeof(uids);
    if (type == 'object') { // 多用户
        var url = TS.API + '/users/';

        var _uids = _.chunk(uids, 20);
        _.forEach(_uids, function(value, key) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {id: _.join(value, ',')},
                async: false,
                success:function(res){
                    user = _.unionWith(user, res);
                }
            }, 'json');
        })
    } else {
        var url = TS.API + '/users/' + uids;
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            success:function(res){
                user = res;
            }
        }, 'json');
    }
    return user;
}

// 获取用户头像
var getAvatar = function(user, width) {
    width = width || 0;
    var avatar = '';
    if (user['avatar']) {
        avatar = user['avatar'];
    } else {
        switch (user['sex']) {
            case 1:
                avatar = TS.RESOURCE_URL + '/images/pic_default_man.png';
                break;
            case 2:
                avatar = TS.RESOURCE_URL + '/images/pic_default_woman.png';
                break;
            default:
                avatar = TS.RESOURCE_URL + '/images/pic_default_secret.png';
                break;
        }
    }
    if (width > 0) {
        avatar += '?s=' + width;
    }

    return avatar;
}

// 获取事件
var getEvent = function() {
    if(window.event)    {return window.event;}
    func=getEvent.caller;
    while(func!=null){
        var arg0=func.arguments[0];
        if(arg0){
            if((arg0.constructor==Event || arg0.constructor ==MouseEvent
                || arg0.constructor==KeyboardEvent)
                ||(typeof(arg0)=="object" && arg0.preventDefault
                && arg0.stopPropagation)){
                return arg0;
            }
        }
        func=func.caller;
    }
    return null;
}

// 阻止冒泡
var cancelBubble = function() {
    var e=getEvent();
    if(window.event){
        //e.returnValue=false;//阻止自身行为
        e.cancelBubble=true;//阻止冒泡
    }else if(e.preventDefault){
        //e.preventDefault();//阻止自身行为
        e.stopPropagation();//阻止冒泡
    }
}

// 字数计算
var strLen = function (str){
    str = str.replace(/(\s+)|([\r\n])/g, '');
    var len = 0;
    for (var i=0; i<str.length; i++) {
        var c = str.charCodeAt(i);
        //单字节加1
        if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
            len++;
        }
        else {
            len+=2;
        }
    }
    return len;
};

$(function() {
    // Jquery fixed拓展
    jQuery.fn.fixed = function(options) {
        var defaults = {
            x:0,
            y:0
        };
        var o = jQuery.extend(defaults, options);
        var isIe6 = !window.XMLHttpRequest;
        var html= $('html');
        if (isIe6 && html.css('backgroundAttachment') !== 'fixed') {
            html.css('backgroundAttachment','fixed').css('backgroundImage','url(about:blank)');
        };
        return this.each(function() {
            var domThis=$(this)[0];
            var objThis=$(this);
            if(isIe6){
                objThis.css('position' , 'absolute');
                domThis.style.setExpression('right', 'eval((document.documentElement).scrollRight + ' + o.x + ') + "px"');
                domThis.style.setExpression('top', 'eval((document.documentElement).scrollTop + ' + o.y + ') + "px"');
            } else {
                objThis.css('position' , 'fixed').css('top',o.y).css('right',o.x);
            }
        });
    };

    // 右侧边栏
    if (TS.MID != 0 && !browser.versions.mobile) {
        var _st = $.cookie("ms_fixed");
        if (!_st) _st=0;
        var _code = '<div id="ms_fixed_wrap">'
                  + '<dl id="ms_fixed">'
                  +   '<dd id="ms_comments"><a href="javascript:;" onclick="easemob.openChatDialog(0)"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-side-msg"></use></svg></a></dd>'
                  +   '<dd id="ms_likes"><a href="javascript:;" onclick="easemob.openChatDialog(1)"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-side-like"></use></svg></a></dd>'
                  +   '<dd id="ms_notifications"><a href="javascript:;" onclick="easemob.openChatDialog(2)"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-side-notice"></use></svg></a></dd>'
                  +   '<dd id="ms_pinneds"><a href="javascript:;" onclick="easemob.openChatDialog(3)"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-side-auth"></use></svg></a></dd>'
                  + '</dl>'
                  + '</div>';
        if (_st == 1) {
            $(_code).hide().appendTo("body").fixed({x:-44,y:0}).fadeIn(500);
        } else {
            $(_code).hide().appendTo("body").fixed({x:0,y:0}).fadeIn(500);
        }
    }

    // 获得用户时区与GMT时区的差值
    if ($.cookie('customer_timezone') == '') {
        var exp = new Date();
        var gmtHours = -(exp.getTimezoneOffset()/60);
        $.cookie('customer_timezone', gmtHours, 1);
    }

    // 二级导航
    $('.nav_list .navs li').hover(function(){
        $(this).find('.child_navs').show();
    },function(){
        $(this).find('.child_navs').hide();
    })

    // 个人中心展开
    $('.nav_right').hover(function(e) {
        if (e.type == 'mouseleave' && $('.nav_menu').css('display') == 'block') {
            $('.nav_menu').hide();
        }
        if (e.type == 'mouseenter' && $('.nav_menu').css('display') == 'none') {
            $('.nav_menu').show();
        }
    })

    // 跳至顶部
    $('#gotop').click(function() {
        $(window).scrollTop(0);
    })

    // 弹出层点击其他地方关闭
    $('body').click(function(e) {
        var target = $(e.target);
        // 个人中心
        if(!target.is('#menu_toggle') && target.parents('.nav_menu').length == 0) {
           $('.nav_menu').hide();
        }

        // 更多按钮
        if(!target.is('.icon-more') && target.parents('.options_div').length == 0) {
           $('.options_div').hide();
        }

        // 投稿
        if (!target.is('.release_tags_selected') && !target.is('dl,dt,dd,li')) {
            $('.release_tags_list').hide();
        }

        // 顶部搜索
        if (!target.is('.head_search') && target.parents('.head_search').length == 0 && target.parents('.nav_search').length == 0) {
            $('.head_search').hide();
        }

        // 分享
        if(!target.is('div.share-show,button.show-share') && target.parents('.share-show').length == 0) {
            $('.share-show').fadeOut();
        }

        if(!target.is('.u-share, .u-share-show') && !target.is('.u-share svg') && target.parents('.u-share-show').length == 0) {
            $('.u-share-show').fadeOut();
        }

        // 相关问题
        if(!target.is('div.question-searching') && target.parents('.question-searching').length == 0) {
            $('.question-searching').fadeOut();
        }

        // 问题话题
        if(!target.is('div.question-topics-list') && !target.is('dl,dt,dd,li')) {
            $('.question-topics-list').hide();
        }

        // 圈子管理
        if(!target.is('.u-menu li') && !target.is('.u-opt svg')) {
            $('.u-menu').fadeOut();
        }
    });

    // 显示隐藏评论操作
    $(document).on("mouseover mouseout",".comment_con, .reply_body",function(event){
        if(event.type == "mouseover"){
            $(this).find("a.mouse").show();
        }else if(event.type == "mouseout"){
            $(this).find("a.mouse").hide();
        }
    });

    // 顶部搜索
    var head_last;

    // 搜索输入
    $("#head_search").keyup(function(event){
        //利用event的timeStamp来标记时间，这样每次的keyup事件都会修改last的值
        head_last = event.timeStamp;
        console.log(head_last)
        setTimeout(function(){
            if(head_last - event.timeStamp == 0){
                head_search();
            }
        }, 500);
    });

    // 搜索聚焦
    $("#head_search").focus(function() {
        var val = $.trim($("#head_search").val());
        $('.head_search').show();
        if (val.length >= 1) {
            $('.history').hide();
            head_search();
        } else {
            $('.search_types').hide();
            // 显示历史记录
            var hisArr = getHistory();
            if (hisArr.length > 0) {
                $('.history').show();
                var ul = $('.history ul');
                var lis = '';

                for (var i = 0, len = (hisArr.length > 10 ? 10 : hisArr.length); i < len; i++) {
                    lis += '<li type="1"><span class="keywords">' + hisArr[i] + '</span><i></i></li>';
                }

                ul.html('').append(lis);
            }
        }
    });

    // 显示搜索选项
    function head_search() {
        var val = $.trim($("#head_search").val());
        if (val == '') {
            $('.head_search').hide();
            $('.search_types').hide();
        } else {
            $('.history').hide();
            $('.head_search').show();
            $('.search_types .keywords').text(val);
            $('.search_types').show();
        }
    }

    // 选项点击
    $('.head_search').on('click', 'span', function() {
        var val = $(this).parents('li').find('.keywords').text();
        if ($(this).parents('.search_types')) {
            setHistory(val);
        }

        var type = $(this).parents('li').attr('type');
        window.location.href = '/search/' + type + '/' + val;
    });

    // 删除历史记录
    $('.head_search').on('click', 'i', function() {
        var val = $(this).siblings('span').text();
        delHistory(val);

        if ($(this).parent().siblings().length == 0) {
            $('.history').hide();
            $('head_search').hide();
        }
        $(this).parent().hide();
    });

    // 近期热点
    if($('.time_menu li a').length > 0) {
        $('.time_menu li').hover(function() {
            var type = $(this).attr('type');

            $(this).siblings().find('a').removeClass('hover');
            $(this).find('a').addClass('hover');

            $('.hot_news_list .hot_news_item').addClass('hide');
            $('#' + type).removeClass('hide');
        })
    }

    // 搜索图标点击
    $('.nav_search_icon').click(function(){
        var val = $('#head_search').val();
        setHistory(val);
        window.location.href = '/search/1/' + val;
    })

    // 下拉框
    var select = $(".zy_select");
    if (select.length > 0) {
        select.on("click", function(e){
            e.stopPropagation();
            if ($(this).hasClass("select-gray")) {
                $(this).removeClass("select-gray");
                $(this).siblings('.zy_select').addClass('select-gray');
            }
            if (!$(this).hasClass("open")) {
                $(this).siblings('.zy_select').removeClass('open');
                $(this).addClass("open");
            } else {
                $(this).removeClass("open")
            }
            return;
        });

        select.on("click", "li", function(e){
            e.stopPropagation();
            var $this = $(this).parent("ul");
            $(this).addClass("active").siblings(".active").removeClass("active");
            $this.prev('span').html($(this).html());
            $this.parent(".zy_select").removeClass("open");
            $this.parent(".zy_select").data("value", $(this).data("value"));
        });

        $(document).click(function() {
            select.removeClass("open");
        });
    }

    // 置顶弹窗
    $(document).on('click', '.pinned_spans span', function() {
        $(this).siblings().removeClass('current');
        $(this).addClass('current');

        var days = $(this).attr('days');
        var amount = $('.pinned_input input').val();

        if (amount != '') $('.pinned_total span').html(days*amount);
    });

    $(document).on('focus keyup change', '.pinned_input input', function() {
        var days = $('.pinned_spans span.current').length > 0 ? $('.pinned_spans span.current').attr('days') : '';
        var amount = $(this).val();

        if (days != '') $('.pinned_total span').html(days*amount);
    });

    // 打赏弹窗
    $(document).on('click', '.reward_spans span', function() {
        $('.reward_input input').val('');
        $(this).siblings().removeClass('current');
        $(this).addClass('current');
    });

    // 显示回复框
    $(document).on('click', '.J-comment-show', function() {
        checkLogin();

        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });

    // 显示跳转详情文字
    $(document).on("mouseover mouseout", '.date', function(event){
        if(event.type == "mouseover"){
          var width = $(this).find('span').first().width();
            width = width < 60 ? 60 : width;
          $(this).find('span').first().hide();
          $(this).find('span').last().css({display:'inline-block', width: width});
        }else if(event.type == "mouseout"){
          $(this).find('span').first().show();
          $(this).find('span').last().hide();
        }
    });

    $(document).on('focus keyup change', '.reward_input input', function() {
        $('.reward_spans span').removeClass('current');
    });

    $(document).on('click', '.click_loading a', function() {
        scroll.clickMore(this);
    });

    $(document).on('mouseover mouseout', '.ms_chat', function(event) {
        var cid = $(this).data('cid');
        var name = $(this).data('name');

        var html = '<div id="ms_chat_tips">' + name + '<div class="tips_triangle"></div></div>';
        var top = $(this).offset().top;
        if (event.type == 'mouseover') {
            $(this).addClass('tips_current');

            $('#ms_fixed_wrap').after(html);
            $('#ms_chat_tips').css({"top": top + 9}).fadeIn('fast');
        } else {
            $(this).removeClass('tips_current');

            $('#ms_chat_tips').remove();
        }
    });

    // IM聊天
    if (TS.MID > 0 && TS.BOOT['im:serve']) {
        // 聊天初始化
        easemob.init();
    }

    // 回车事件绑定
    document.onkeyup = function(e){
        e = e || window.event;
        // 回车
        if(e.keyCode == 13){
            var target = e.target || e.srcElment;
            if(target.id == 'head_search'){ // 搜索
                var val = $('#head_search').val();
                setHistory(val);
                window.location.href = '/search/1/' + val;
            }else if(target.id == 'l_login' || target.id == 'l_password'){ // 登录
                $('#login_btn').click();
            }
        }

        // ctrl + 回车发送消息
        if(e.ctrlKey && e.keyCode == 13 && strLen($('#chat_text').val()) != 0) {
            $('#chat_send').click();
        }
    }

});