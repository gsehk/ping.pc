﻿


var news = {};

news.setting = {};

/**
 * 文章初始化
 * $param object option 文章配置相关数据
 * @return void
 */
news.init = function(option)
{
  this.setting.container = option.container;        // 容器ID
  this.setting.loadcount = option.loadcount || 0;       // 加载次数
  this.setting.loadmax = option.loadmax || 4;         // 加载最大次数
  this.setting.maxid = option.maxid || 0;         // 最大文章ID
  this.setting.loadlimit = option.loadlimit || 10;      // 每次加载的数目，默认为10
  this.setting.cid = option.cid || 0;             //  文章分类ID
  this.setting.canload = option.canload || true;        // 是否能加载

  news.bindScroll();

  if($(news.setting.container).length > 0 && this.setting.canload){
    // $(news.setting.container).append("<div class='loading' id='loadMore'><img src='/image/ico-load.png' class='load'></div>");
    news.loadMore();
  }
};
/**
 * 页面底部触发事件
 * @return void
 */
news.bindScroll = function()
{
  // 底部触发事件绑定
  $(window).bind('scroll resize', function() {

    // 加载指定次数后，将不能自动加载
    if (news.isLoading()) {
      var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
      var bodyHeight = $(document.body).height();
      if(bodyTop + $(window).height() >= bodyHeight - 250) {
          if($(news.setting.container).length > 0) {
            $(news.setting.container).append("<div class='loading' id='loadMore'><img src='zhiyicx/plus-component-pc/images/loading.png' class='load'>加载中</div>");
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
news.isLoading = function()
{
  var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
  return status;
};

/**
 * 获取加载的数据信息
 * @return void
 */
news.loadMore = function()
{
  // 将能加载参数关闭
  news.setting.canload = false;
  news.setting.loadcount++;
  // 异步提交，获取相关频道数据
  var postArgs = {};
  postArgs.max_id = news.setting.maxid;
  postArgs.limit = news.setting.loadlimit;
  postArgs.cate_id = news.setting.cid;
  $.get('/information/getNewsList', postArgs, function(res) {
    if (res.data.length > 0) {
      news.setting.canload = true;
      // 修改加载ID
      news.setting.maxid = res.data[res.data.length-1].id;
      var html = '';
      var data = res.data;
      for(var i in data) {
        html += '<div class="inf_list">'+
        '<div class="inf_img">'+
          '<a href="@{{Route(\'pc:newsdetail\')}}">'+'<img src="http://tsplus.zhibocloud.cn/api/v1/storages/'+data[i].storage+'/30" />'+'</a>'+
        '</div>'+
        '<div class="inf_word">'+
          '<a href="{{Route(\'pc:newsdetail\')}}">'+
            '<div class="infW_title">'+data[i].title+'</div>'+
          '</a>'+
          '<p>'+data[i].title+'</p>'+
          '<div class="inf_bm">'+
            '<span class="inf_time">'+data[i].updated_at+'</span>'+
            '<span class="inf_comment">'+data[i].comment_count+'评论<span>|</span>'+data[i].collection.length+'收藏</span>'+
          '</div>'+
        '</div>'+
        '</div> ';
      }
      if (news.setting.loadcount == 1) {
        $(news.setting.container).html(html);
      } else {
        $(news.setting.container).append(html);
      }
      
    } else {
      news.setting.canload = false;
      $('#loadMore').html('没有了~');
    }
  });
};

/** 
 * 获取热点文章
 * @param  type (1-本周 2-当月 3-季度)
 */
var recent_hot = function (type) {
    if (type != undefined) {
      $.get('/information/getRecentHot', {type:type}, function(res) {
          if (res.data.length > 0) {
            var html = '', f = 1;
            var data = res.data;
            for(var i in data){
              html += '<li>'+
                '<span>'+f+'</span>'+
                '<a href="javascript:;">'+data[i].title+type+'</a>'+
              '</li>';
              f++;
            }
            $('#j-recent-hot-wrapp').html(html);
          } else {
            $('#j-recent-hot-wrapp').html('<div class="loading">暂无数据~</div>');
          }
      });
    }
};

/**
 * 获取热门作者
 */
var author_hot = function () {
    $.get('/information/getAuthorHot', function(res) {
        if (res.data.length > 0) {
          var html = '';
          var data = res.data;
          for(var i in data){
            html += '<div class="R_list">'+
              '<div class="i_left">'+
              '<img src="" />'+
              '</div>'+
              '<div class="i_right">'+
              '<span>'+data[i].author+' <img src="" class="vip_icon" /></span>'+
              '<p>'+data[i].desc+'</p>'+
              '</div>'+
              '</div>';
          }
          $('#j-author-hot-wrapp').html(html);
        } else {
          $('#j-author-hot-wrapp').html('<div class="loading">暂无数据~</div>');
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
    var formData = new FormData($( "#release_form" )[0]); 
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
                            console.log(response);
                        }
                    });
                  } 
              }); 
            }else{
                console.log(res);
            }
        }
    }); 
    /*$.getScript(obj.data('js'), function() {
        $.ajaxFileUpload({
            url: '/api/v1/storages/task',
            fileElementId: obj.attr('id'),
            secureuri: false,
            dataType: 'json',
            type: 'POST',
            data: args,
            beforeSend: function (xhr) {
    　　　　  xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　　　},
            error: function() {
                alert('提交错误，请刷新页面重新尝试！');
            },
            success: function(res) {
              console.log(res);
              if (res.status) {
                // $(obj.data('input')).val(data.data.attach_id);
              }
            }
        });
    });*/
};

$('#J-file-upload').on('change', {id:'J-file-upload', callback:ajaxFileUpload},getImgInfo);

$('.subject-submit').on('click', function(){
  var $this = $(this);
  var subject  = $('#subject-title'),
      logo     = $('#form_logo'),
      abstract = $('#subject-abstract'),
      froms    = $('#subject-from'), 
      url      = $this.data('url');
      var args  = {
        'subject' : subject.val(),
        'storage'    : logo.val(),
        'abstract': abstract.val(),
        'content' : $(editor).html(),
        'source'  : froms.val(),
        '_token'  : $('#token').val()
      };

      $.post(url, args, function(data) {
        if (data.status == true) {
          window.location.href = data.data.url;
        } else {  
          alert(data.data.message);
        };
    }, 'json');
});

$(document).ready(function(){
  recent_hot(1);
  $('#j-recent-hot .week').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(1);
  });
  $('#j-recent-hot .meth').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(2);
  });
  $('#j-recent-hot .moth').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(3);
  });
});
