
var weibo = {};

weibo.setting = {};

/**
 * 微博初始化
 * $param object option 微博配置相关数据
 * @return void
 */
weibo.init = function(option)
{
    this.setting.container = option.container;        // 容器ID
    this.setting.loadcount = option.loadcount || 0;       // 加载次数
    this.setting.loadmax = option.loadmax || 40;         // 加载最大次数
    this.setting.maxid = option.maxid || 0;         // 最大微博ID
    this.setting.loadlimit = option.loadlimit || 10;      // 每次加载的数目，默认为10
    this.setting.cid = option.cid || 0;             //  微博分类ID
    this.setting.canload = option.canload || true;        // 是否能加载
    this.setting.page = option.page || 1;        // 页码
    this.setting.loading = option.loading || '.dy_cen';        //加载图位置
    if (option.cid) {
        switch(parseInt(option.cid))
        {
            /* 热门微博 */
            case 2:
                this.setting.url = '/home/hots';
            break;
            /* 最新微博 */
            case 3:
                this.setting.url = '/home/feeds';
            break;
            /* 关注的微博 */
          default:
                this.setting.url = '/home/follows';
        }
    }

    weibo.bindScroll();

    if($(weibo.setting.container).length > 0 && this.setting.canload){
        $(weibo.setting.loading).after(loadHtml);
        // $(weibo.setting.container).append(loadHtml);
        weibo.loadMore();
    }
};
/**
 * 页面底部触发事件
 * @return void
 */
weibo.bindScroll = function()
{
    // 底部触发事件绑定
    $(window).bind('scroll resize', function() {

      // 加载指定次数后，将不能自动加载
      if (weibo.isLoading()) {
          var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
          var bodyHeight = $(document.body).height();
          if(bodyTop + $(window).height() >= bodyHeight - 250) {
              if($(weibo.setting.container).length > 0) {
                  $(weibo.setting.loading).after(loadHtml);
                  // $(weibo.setting.container).append(loadHtml);
                  weibo.loadMore();
              }
        }
      }
    });
};

/**
 * 判断是否微博时候能自动加载
 * @return boolean 微博是否能自动加载
 */
weibo.isLoading = function()
{
    var status = (this.setting.loadcount >= this.setting.loadmax || this.setting.canload == false) ? false : true;
    return status;
};

/**
 * 获取加载的数据信息
 * @return void
 */
weibo.loadMore = function()
{
    // 将能加载参数关闭
    weibo.setting.canload = false;
    weibo.setting.loadcount++;

    var postArgs = {};
    postArgs.max_id = weibo.setting.maxid;
    postArgs.limit = weibo.setting.loadlimit;
    postArgs.cate_id = weibo.setting.cid;
    postArgs.page = weibo.setting.page;
    $.ajax({
        url: weibo.setting.url,
        type: 'GET',
        data: postArgs,
        dataType: 'json',
        error:function(xml){},
        success:function(res){
            if (res.data.maxid > 0) {
              weibo.setting.canload = true;
              // 修改加载ID
              weibo.setting.page++;
              weibo.setting.maxid = res.data.maxid;
              var html = res.data.html;
              if (weibo.setting.loadcount == 1) {
                  $(weibo.setting.container).html(html);
                  $('.loading').remove();
              } else {
                  $(weibo.settingng.container).append(html);
              }
            } else {
              weibo.setting.canload = false;
              if (weibo.setting.loadcount == 1) {
                no_data(weibo.setting.container, 1, ' 暂无相关内容');
                $('.loading').html('');
              }else{
                $('.loading').html('暂无相关内容');
              }
            }
        }
    });
};

/**
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" src="' + image.src + '" tid="' + task_id + '"/>';
    var del = '<span class="imgdel"><i class="icon iconfont icon-close"></i></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};

/**
 * 发布动态
 * @return void
 */
weibo.postFeed = function() {
    if (MID == 0) {
        window.location.href = '/passport/index';
        return false;
    }

    var storage_task_ids = [];
    $('.dy_picture').find('img').each(function(){
        storage_task_ids.push($(this).attr('tid'));
    });

    var data = {
        feed_content: $('#feed_content').val(),
        storage_task_ids: storage_task_ids,
        feed_from: 1,
        isatuser: 0
    }

    var url = API + '/feeds';
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(res) {
            noticebox('发布成功', 1);
            $('.dy_picture').html('').hide();
            $('#feed_content').val('');

            weibo.afterPostFeed(res.data);
        }

    })
};

weibo.afterPostFeed = function(feed_id) {
    var url = '/home/' + feed_id + '/feedinfo';
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(res) {
            $(res.data.html).hide().prependTo('#feeds-list').fadeIn('slow');
        }

    })
}


/**
 * 核心评论对象
 */
var comment = {
  // 初始化评论对象
  init: function(attrs) {
    this.row_id = attrs.row_id || 0;
    this.max_id = attrs.max_id || 0;
    this.limit = attrs.limit || 5;
    this.to_uid = attrs.to_uid || 0;
    this.canload = attrs.canload || 1;
    this.reply_to_user_id = attrs.reply_to_user_id || 0;
    this.addToEnd = attrs.addToEnd || 0;
    this.box = attrs.box || '#comment_detail';
    this.editor = attrs.editor || '#mini_editor';

    this.bindScroll();

    if($(this.box).length > 0 && this.canload > 0){
        $(this.box).append(loadHtml);
        comment.loadMore();
    }
  },
   // 页面底部触发事件
  bindScroll:function()
  {
    // 底部触发事件绑定
    $(window).bind('scroll resize', function() {
      if (comment.canload == true) {
        var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
        var bodyHeight = $(document.body).height();
        if(bodyTop + $(window).height() >= bodyHeight - 250) {
            if($(comment.box).length > 0) {
              $(comment.box).append(loadHtml);
              comment.loadMore();
          }
        }
      }
    });
  },
   // 显示回复块
  loadMore:function(){
      comment.canload = false;
      var url = request_url.get_feed_commnet.replace('{feed_id}', comment.row_id);
      $.ajax({
        url: url,
        type: 'GET',
        data: {max_id:comment.max_id, limit:comment.limit},
        dataType: 'json',
        error:function(xml){},
        success:function(res){
            if (res.data.length > 0) {
              comment.canload = true;
              comment.max_id = res.data[res.data.length-1].id;
              var data = res.data,
                  html = '';
                for(var i in data) {
                  var  avatar = data[i].uinfo.avatar ? API+'/storages/'+data[i].uinfo.avatar : AVATAR;
                  html += '<div class="delComment_list">';
                  html +='<div class="comment_left">';
                  html +='<img src="'+avatar+'" class="c_leftImg" />';
                  html +='</div>';
                  html +='<div class="comment_right">';
                  html +='<span class="del_ellen">'+data[i].uinfo.name+'</span>';
                  html +='<span class="c_time">'+data[i].created_at+'</span>';
                  html +='<i class="icon iconfont icon-gengduo-copy"></i>';
                  html +='<p>'+data[i].comment_content+'';
                  if (data[i].user_id != MID) {
                    html +='<span class="del_huifu">';
                    html +='<a href="javascript:void(0)" data-args="editor=#mini_editor&box=#comment_detail&to_comment_uname='+data[i].uinfo.name+'&canload=0&to_uid='+data[i].user_id+'"';
                    html +='class="J-reply-comment">回复';
                    html +='</a></span>';
                  }
                  html +='</p></div></div>';
                }
                $(comment.box).append(html);
                $('.loading').remove();
                $('.J-reply-comment').on('click', function(){
                  var attrs = urlToObject($(this).data('args'));
                  comment.initReadReply(attrs);
                });
            } else {
              comment.canload = false;
              $('.loading').html('暂无相关内容');
            }
        }
      });
  },

  // 显示编辑框
  display:function(attr){
      var feedid = attr.row_id;
      var editor_box = $('#editor_box'+feedid);
      var comment_box = $('#comment_box'+feedid);
      if (editor_box.length > 0) {
          return false;
      }

      var boxhtml = '<div class="dyBox_comment" id="editor_box'+feedid+'">'+
                    '<textarea placeholder="" class="comment-editor"></textarea>'+
                    '<div class="dy_company">'+
                    '<span class="fs-14">'+
                    '<svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>表情</span>'+
                    '<span class="dy_cs">可输入<span class="nums">255</span>字</span>'+
                    '<a href="javascript:;" class="dy_share a_link J-comment-feed'+feedid+'" to_uid="0" row_id='+feedid+'>评论</a>'+
                    '</div>'+
                    '</div>';
      comment_box.prepend(boxhtml);

      $('.J-comment-feed'+feedid).on('click', function(){
          comment.addComment(null, this);
      });
  },
  // 初始化回复操作
  initReply: function(obj) {
    $('.J-comment-feed'+obj.row_id).attr('to_uid', obj.to_uid);
    var _textarea = $('#editor_box'+obj.row_id).find('textarea');
    if(_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
    var html = '回复@'+obj.to_uname+' ：';
    //清空输入框
    _textarea.val('');
    _textarea.val(html);
    _textarea.focus();
  },
    // 初始化回复操作
  initReadReply: function(obj) {
    $('#J-comment-news').attr('to_uid', obj.to_uid);
    var _textarea = $(obj.editor);
    if(_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
    var html = '回复@'+obj.to_comment_uname+' ：';
    //清空输入框
    _textarea.val('');
    _textarea.val(html);
    _textarea.focus();
  },

  // 列表发表评论
  addComment:function(afterComment,obj) {
    var to_uid = $(obj).attr('to_uid') || 0;
    var feedid = $(obj).attr('row_id') || 0;
    var _textarea = $('#editor_box'+feedid).find('textarea');
    
    if(_textarea.size() == 0) {
      _textarea = $(obj).parent().find('input:eq(0)');
    }
    _textarea = _textarea.get(0);
    var strlen = getLength(_textarea.value);
    var leftnums = initNums - strlen;
    if(leftnums < 0 || leftnums == initNums) {
      alert('内容长度为1-255个字符');
      return false;
    }

    var content = _textarea.value;  
    if(content == '') {
      alert('内容不能为空');
    }

    if("undefined" != typeof(this.addComment) && (this.addComment == true)) {
      return false; //不要重复评论
    }

    var url = request_url.feed_comment.replace('{feed_id}', feedid);

    obj.innerHTML = '回复中..';
    
    $.ajax({
        url: url,
        type: 'POST',
        data:{comment_content:content,reply_to_user_id:to_uid},
        dataType: 'json',
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              if ( obj != undefined ){
                obj.innerHTML = '回复';
              }
              var html = '<p><span>'+NAME+'：</span>'+content+'</p>';
              var commentBox = $('.comment_box'+feedid);
              if("undefined" != typeof(commentBox)){
                commentBox.prepend(html);
                _textarea.value = '';
              }
          }else{
              alert(res.message);
          }
        }
    });
  },
    // 详情发表评论
  addReadComment:function(afterComment,obj) {
    var box = this.box;
    var _textarea = $('#mini_editor');
    if(_textarea.size() == 0) {
      return;
    }
    _textarea = _textarea.get(0);
    var strlen = getLength(_textarea.value);

    var leftnums = initNums - strlen;
    if(leftnums < 0 || leftnums == initNums) {
      alert('内容不能大于255个字符');
      return false;
    }

    var content = _textarea.value;  
    if(content == '') {
      alert('内容不能为空');
    }
    if("undefined" != typeof(this.addReadComment) && (this.addReadComment == true)) {
      return false; //不要重复评论
    }
    var addToEnd = this.addToEnd;
    var url = request_url.feed_comment.replace('{feed_id}',this.row_id);
    var _this = this;
    obj.innerHTML = '回复中..';

    $.ajax({
        url: url,
        type: 'POST',
        data:{comment_content:content,reply_to_user_id:this.to_uid},
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              if ( obj != undefined ){
                obj.innerHTML = '回复';
              }
              var html = '<div class="delComment_list">'+
                '<div class="comment_left">'+
                '<img src="'+AVATAR+'" class="c_leftImg" />'+
                '</div>'+
                '<div class="comment_right">'+
                '<span class="del_ellen">'+NAME+'</span>'+
                '<span class="c_time">刚刚</span>'+
                '<i class="icon iconfont icon-gengduo-copy"></i>'+
                '<p>'+content+'</p>'+
                '</div>'+
                '</div>';
              var commentBox = $(comment.box);
              if("undefined" != typeof(commentBox)){
                if(addToEnd == 1){
                  commentBox.append(html);
                }else{
                  commentBox.prepend(html);
                }
                /*绑定回复操作*/
                $('.J-reply-comment').on('click', function(){
                    comment.initReply();
                });
              }
          }else{
              alert(res.message);
          }
        }
    });
  },
  delComment:function(comment_id){
    $.post(U('widget/Comment/delcomment'),{comment_id:comment_id},function(msg){
      // 动态添加字数
      var commentDom = $('#feed'+comment.row_id).find('a[event-node="comment"]');
      var oldHtml = commentDom.html();
      if (oldHtml != null) {
        var commentVal = oldHtml.replace(/\(\d+\)$/, function (num) {
          var cnum = parseInt(num.slice(1, -1)) - 1;
          if (cnum <= 0) {
            return '';
          }
          num = '(' + cnum + ')';
          return num;
        });
        commentDom.html(commentVal);
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
  _init: function (attrs) {
    digg.init();
  },
  init: function () {
    digg.digglock = 0;
  },
  addDigg: function (feed_id) {
    // 未登录弹出弹出层
    if(MID == 0){
          alert('小伙子你还没登录~~');
      return;
    }
    
    if (digg.digglock == 1) {
      return false;
    }
    digg.digglock = 1;

    var url = request_url.digg_feed.replace('{feed_id}', feed_id);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              $digg = $('#digg'+feed_id);
              var num = $digg.attr('rel');
              num++;
              $digg.attr('rel', num);
              $('#digg'+feed_id).html('<a href="javascript:;" onclick="digg.delDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font>'+num+'</font></a>');
          }else{
              alert(res.message);
          }

          digg.digglock = 0;
        }
    });

  },
  delDigg: function (feed_id) {
    if (digg.digglock == 1) {
      return false;
    }
    digg.digglock = 1;

    var url = request_url.digg_feed.replace('{feed_id}', feed_id);
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        error:function(xml){},
        success:function(res, data, xml){
          if (xml.status == 204) {
              $digg = $('#digg'+feed_id);
              var num = $digg.attr('rel');
              num--;
              $digg.attr('rel', num);
              $('#digg'+feed_id).html('<a href="javascript:;" onclick="digg.addDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>'+num+'</font></a>');
          }else{
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
  _init: function (attrs) {
    collect.init();
  },
  init: function () {
    collect.collectlock = 0;
  },
  addCollect: function (feed_id) {
    // 未登录弹出弹出层
    if(MID == 0){
          alert('小伙子你还没登录~~');
      return;
    }
    
    if (collect.collectlock == 1) {
      return false;
    }
    collect.collectlock = 1;

    var url = request_url.collect_feed.replace('{feed_id}', feed_id);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              $collect = $('#collect'+feed_id);
              var num = $collect.attr('rel');
              num++;
              $collect.attr('rel', num);
              $('#collect'+feed_id).html('<a href="javascript:;" onclick="collect.delCollect('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="collect_num">'+num+'</font>人收藏</a>');
          }else{
              alert(res.message);
          }

          collect.collectlock = 0;
        }
    });

  },
  delCollect: function (feed_id) {

    if (collect.collectlock == 1) {
      return false;
    }
    collect.collectlock = 1;
    var url = request_url.collect_feed.replace('{feed_id}', feed_id);
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        error:function(xml){},
        success:function(res, data, xml){
          if (xml.status == 204) {
              $collect = $('#collect'+feed_id);
              var num = $collect.attr('rel');
              num--;
              $collect.attr('rel', num);
              $('#collect'+feed_id).html('<a href="javascript:;" onclick="collect.addCollect('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="collect_num">'+num+'</font>人收藏</a>');
          }else{
              alert(res.message);
          }

          collect.collectlock = 0;
        }
    });
  }
};

// 图片删除时间绑定
$(function(){
    $(".dy_cTop").on("click", ".imgdel", function(){
        $(this).parent().remove();
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
            $('#file_upload_1-queue').hide();
        }
    });
    //为删除文件按钮绑定删除文件事件
    $(".dy_cTop").on("click", ".imgdel", function(){
        $(this).parent().remove();
　　});

    // 微博分类tab
    $('.show_tab a').on('click', function(){
        var cid = $(this).data('cid');
        $('#feeds-list').html('');
        weibo.init({container: '#feeds-list',cid: cid});
        $('.show_tab a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });
    // 微博操作菜单
    $('#feeds-list').on('click', '.show_admin', function(){
        if ($(this).next('.cen_more').css('display') == 'none') {
            $(this).next('.cen_more').show();
        } else {
            $(this).next('.cen_more').hide();
        }
    });

    // 显示回复框
    $('#feeds-list').on('click', '.J-comment-show', function(){
        var attrs = urlToObject($(this).data('args'));
        if ($(attrs.box).css('display') == 'none') {
            $(attrs.box).show();
            comment.display(attrs);
        }else{
            $(attrs.box).hide();
        }
    });

    // 回复初始化
    $('#feeds-list').on('click', '.J-reply-comment', function(){
      var attrs = urlToObject($(this).data('args'));

      comment.initReply(attrs);
    });

});
