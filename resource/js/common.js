// Ajax 设置csrf Header
$.ajaxSetup({
   headers: {
       'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
   }
});

var args = {
  data: {},
  set: function(name, value) {
    this.data[name] = value;
    return this;
  },
  get: function () {
    return this.data;
  }
};

var urlToObject=function(url){   
  var urlObject = {};    
    var urlString=url.substring(url.indexOf("?")+1);   
    var urlArray=urlString.split("&");   
    for(var i=0,len=urlArray.length;i<len;i++){   
      var urlItem=urlArray[i];   
      var item = urlItem.split("=");   
      urlObject[item[0]]=item[1];   
    }
    
    return urlObject;      
};

// 字符串长度 - 中文和全角符号为1；英文、数字和半角为0.5
var getLength = function(str, shortUrl) {
  if (true == shortUrl) {
    // 一个URL当作十个字长度计算
    return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
              .replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
  } else {
    return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
  }
};

// 文件上传
var fileUpload = function(f, callback){
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = e.target.result;
        //加载图片获取图片真实宽度和高度
        var image = new Image();
        image.onload=function(){
            var width = image.width;
            var height = image.height;
            var size = f.size;
            doFileUpload(image, f, callback);
        };
        image.src= data;
   };
   reader.readAsDataURL(f);
};

var doFileUpload = function(image, f, callback) {
    var args = {
        width  : image.width,
        height : image.height,
        mime_type : f.type,
        origin_filename : f.name,
        hash   : CryptoJS.MD5(f.name).toString(),
    };
    console.log(USER.token);
    // 创建存储任务
    $.ajax({  
        url: '/api/v1/storages/task' ,  
        type: 'POST', 
        async: false,  
        data: args,
        beforeSend: function (xhr) {
  　　　　  xhr.setRequestHeader('Authorization', USER.token);
  　　　}, 
        success: function (res) {  
            if (res.data.uri) {
                var formData = new FormData();
                formData.append("file", f);

                if (res.data.options) {
                    for(var i in res.data.options){
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
                      beforeSend: function (xhr) {
                　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
                　　　}, 
                      success: function (data) {

                      // 上传通知 
                        $.ajax({
                            url: '/api/v1/storages/task/'+res.data.storage_task_id,  
                            type: 'PATCH',
                            async: false,  
                            beforeSend: function (xhr) {
                      　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
                      　　　}, 
                            success: function(response){
                                callback(image, f, res.data.storage_task_id);
                            }
                        });
                      } 
                }); 
            }else{
                callback(image, f, res.data.storage_task_id);
            }
        }
    });
};








var getImgInfo = function(event){
    var $this = $(this);
    var callback = event.data.callback;
    var f = document.getElementById(event.data.id).files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = e.target.result;
        //加载图片获取图片真实宽度和高度
        var image = new Image();
        image.onload=function(){
            var width = image.width;
            var height = image.height;
            var size = f.size;
            callback(width, height, f, $this);
        };
        if ($this.data('preview')) {
          $($this.data('preview')).attr('src', data);
        }
        if ($this.data('tips')) {
          var txt = f.name.substr(-10);
          $($this.data('tips')).text(txt);
        }
        image.src= data;
   };
   reader.readAsDataURL(f);
};
var ajaxFileUpload = function(width, height, f, obj) {

    var args = {
        width  : width,
        height : height,
        mime_type : f.type,
        origin_filename : f.name,
        _token : obj.data('token'),
        hash   : CryptoJS.MD5(f.name).toString(),
    };
    var formData = new FormData($(obj.data('form'))[0]);
    // 创建存储任务
    $.ajax({  
        url: '/api/v1/storages/task' ,  
        type: 'POST',  
        data: args,
        beforeSend: function (xhr) {
  　　　　  xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
  　　　}, 
        success: function (res) {  
            if (res.data.uri) {
              if (res.data.options) {
                for(var i in res.data.options){
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
                  beforeSend: function (xhr) {
            　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
            　　　}, 
                  success: function (data) { 
                  // 上传通知 
                    $.ajax({
                        url: '/api/v1/storages/task/'+res.data.storage_task_id,  
                        type: 'PATCH',
                        beforeSend: function (xhr) {
                  　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
                  　　　}, 
                        success: function(response){

                            $(obj.data('input')).val(res.data.storage_task_id);
                        }
                    });
                  } 
              }); 
            }else{
                $(obj.data('input')).val(res.data.storage_task_id);
            }
        }
    });
};


;(function($){
    //默认参数
    var defaluts = {
        select: "select",
        select_text: "select_text",
        select_ul: "select_ul"
    };
    $.fn.extend({
        "select": function(options){
            var opts = $.extend({}, defaluts, options);
            return this.each(function(){
                var $this = $(this);
                //模拟下拉列表
                if ($this.data("value") !== undefined && $this.data("value") !== '') {
                    $this.val($this.data("value"));
                }
                var _html = [];
                _html.push("<div class=\"" + $this.attr('class') + "\">");
                _html.push("<div class=\""+ opts.select_text +"\">" + $this.find(":selected").text() + "</div>");
                _html.push("<ul class=\""+ opts.select_ul +"\">");
                $this.children("option").each(function () {
                    var option = $(this);
                    if($this.data("value") == option.val()){
                        _html.push("<li class=\"cur\" data-value=\"" + option.val() + "\">" + option.text() + "</li>");
                    }else{
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
                select.click(function (event) {
                    $(this).find("." + opts.select_ul).slideToggle().end().siblings("div." + opts.select).find("." + opts.select_ul).slideUp();
                    event.stopPropagation();
                });
                $("body").click(function () {
                    select_ul.slideUp();
                });
                select_ul.on("click", "li", function () {
                    var li = $(this);
                    var val = li.addClass("cur").siblings("li").removeClass("cur").end().data("value").toString();
                    if (val !== $this.val()) {
                        select_text.text(li.text());
                        $this.val(val);
                        $this.attr("data-value",val);
                    }
                });
            });
        }
    });
})(jQuery);

$(function(){
  $('#menu_toggle').click(function(){
    $('.p_cont').toggle();
  })
})

