
var initNums = 255;
var loadHtml = "<div class='loading'><img src='"+PUBLIC_URL +"/images/loading.png' class='load'>加载中</div>";
var request_url = {
    /* 获取文章列表 */
    get_news:'/information/getNewsList',
    digg_news:'/api/v1/news/{news_id}/digg',
    collect_news:'/api/v1/news/{news_id}/collection',
    comment_news:'/api/v1/news/{news_id}/comment',
};
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
    $(news.setting.container).append(loadHtml);
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
            $(news.setting.container).append(loadHtml);
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
          '<a href="/information/read/'+data[i].id+'">'+'<img src="http://tsplus.zhibocloud.cn/api/v1/storages/'+data[i].storage+'/30" />'+'</a>'+
        '</div>'+
        '<div class="inf_word">'+
          '<a href="/information/read/'+data[i].id+'">'+
            '<div class="infW_title">'+data[i].title+'</div>'+
          '</a>'+
          '<p>'+data[i].title+'</p>'+
          '<div class="inf_bm">'+
            '<span class="inf_time">'+data[i].updated_at+'</span>'+
            '<span class="inf_comment">'+data[i].comment_count+'评论<span>|</span>'+data[i].collection_count+'收藏</span>'+
          '</div>'+
        '</div>'+
        '</div> ';
      }
      if (news.setting.loadcount == 1) {
        $(news.setting.container).html(html);
      } else {
        $(news.setting.container).append(html);
        $('.loading').remove();
      }
      
    } else {
      news.setting.canload = false;
      $('.loading').html('没有了~');
    }
  });
};

/* 加载推荐资讯 */
var recommend = {
    opt: {},
    init:function(option){
        this.opt.container = option.container;
        this.opt.limit = option.loadlimit || 6;
        this.opt.cate_id = option.cate_id || 0;
        this.opt.canload = option.canload || true;

        if($(recommend.opt.container).length > 0 && this.opt.canload){
            $(recommend.opt.container).append(loadHtml);
            recommend.loadMore();
        }
    },
    loadMore:function(){
        recommend.opt.canload = false;
        var postArgs = {};
        postArgs.limit = recommend.opt.limit;
        postArgs.cate_id = recommend.opt.cate_id;
        $.ajax({
          url: request_url.get_news,
          type: 'GET',
          data: postArgs,
          dataType: 'json',
          error:function(xml){},
          success:function(res){
              if (res.data.length > 0) {
                recommend.opt.canload = true;
                var data = res.data,
                    html = '';
                  for(var i in data) {
                    html += '<span>'+data[i].title+'</span>';
                  }
                  $(recommend.opt.container).append(html);
                  $('.loading').remove();
              } else {
                recommend.opt.canload = false;
                $('.loading').html('暂时没有更多可显示的内容哟~');
              }
          }
        });
    },
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

$('.subject-submit').on('click', function(){
  var $this = $(this);
  var subject  = $('#subject-title'),
      task_id     = $('#task_id'),
      abstract = $('#subject-abstract'),
      froms    = $('#subject-from'),
      cate    = $('#subject-cate'),
      url      = $this.data('url');
      var args  = {
        'subject' : subject.val(),
        'task_id'    : task_id.val(),
        'cate_id'    : cate.data('value') || 0,
        'abstract': abstract.val(),
        'content' : $(editor).html(),
        'source'  : froms.val(),
        '_token'  : $('#token').val()
      };

      $.post(url, args, function(data) {
        if (data.status == true) {
          window.location.href = data.data.url;
        } else {  
          alert(data.message);
        };
    }, 'json');
});

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
  addDigg: function (news_id) {
    // 未登录弹出弹出层
    if(MID == 0){
          alert('小伙子你还没登录~~');
      return;
    }
    
    if (digg.digglock == 1) {
      return false;
    }
    digg.digglock = 1;

    var url = request_url.digg_news.replace('{news_id}', news_id);
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              $digg = $('#digg'+news_id);
              var num = $digg.attr('rel');
              num++;
              $digg.attr('rel', num);
              $('#digg'+news_id).html('<a href="javascript:;" onclick="digg.delDigg('+news_id+');"><i class="icon iconfont icon-xihuan-white" style="color: red;"></i><font class="digg_num">'+num+'</font>人喜欢</a>');
          }else{
              alert(res.message);
          }

          digg.digglock = 0;
        }
    });

  },
  delDigg: function (news_id) {
    if (digg.digglock == 1) {
      return false;
    }
    digg.digglock = 1;

    var url = request_url.digg_news.replace('{news_id}', news_id);
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(xml){},
        success:function(res, data, xml){
          if (xml.status == 204) {
              $digg = $('#digg'+news_id);
              var num = $digg.attr('rel');
              num--;
              $digg.attr('rel', num);
              $('#digg'+news_id).html('<a href="javascript:;" onclick="digg.addDigg('+news_id+');"><i class="icon iconfont icon-xihuan-white"></i><font class="digg_num">'+num+'</font>人喜欢</a>');
          }else{
              alert(res.message);
          }

          digg.digglock = 0;
        }
    });
  }
};

/**
 * 赞核心Js
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
  addCollect: function (news_id) {
    // 未登录弹出弹出层
    if(MID == 0){
          alert('小伙子你还没登录~~');
      return;
    }
    
    if (collect.collectlock == 1) {
      return false;
    }
    collect.collectlock = 1;

    var url = request_url.collect_news.replace('{news_id}', news_id);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              $collect = $('#collect'+news_id);
              var num = $collect.attr('rel');
              num++;
              $collect.attr('rel', num);
              $('#collect'+news_id).html('<a href="javascript:;" onclick="collect.delCollect('+news_id+');"><i class="icon iconfont icon-shoucang-copy1" style="color: red;"></i><font class="collect_num">'+num+'</font>收藏</a>');
          }else{
              alert(res.message);
          }

          collect.collectlock = 0;
        }
    });

  },
  delCollect: function (news_id) {

    if (collect.collectlock == 1) {
      return false;
    }
    collect.collectlock = 1;
    var url = request_url.collect_news.replace('{news_id}', news_id);
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(xml){},
        success:function(res, data, xml){
          if (xml.status == 204) {
              $collect = $('#collect'+news_id);
              var num = $collect.attr('rel');
              num--;
              $collect.attr('rel', num);
              $('#collect'+news_id).html('<a href="javascript:;" onclick="collect.addCollect('+news_id+');"><i class="icon iconfont icon-shoucang-copy1"></i><font class="collect_num">'+num+'</font>收藏</a>');
          }else{
              alert(res.message);
          }

          collect.collectlock = 0;
        }
    });
  }
};

/**
 * 核心评论对象
 */
var comment = {
  // 给工厂调用的接口
  _init:function(attrs) {
    if(attrs.length == 3) {
      comment.init(attrs[1], attrs[2]);
    } else {
      return false;
    }
  },
  // 初始化评论对象
  init: function(attrs) {
    this.box = attrs.box;
    this.editor = attrs.editor;
    this.row_id = attrs.row_id;
    this.reply_to_user_id = attrs.reply_to_user_id || 0;
  },
  // 显示回复块
  /*display: function(callback) { 
    var box = this.box;
    var infopen = $(box).parent().find('.infopen:first');
    var forwardBox = $(box).parent().find('.forward_box:first');
    if("undefined" == typeof this.table) {
      this.table = 'feed';
    }
    if(forwardBox.size()) forwardBox.hide();
    if(box.style.display == 'none') {
      if(infopen.size()) {
        infopen.show();
        infopen.find('.trigon').css('left',
         $(this.sourceObject).position().left
          + ($(this.sourceObject).width()/2));
      }
      if(box.innerHTML !=''){
        //box.style.display = 'block';
        $(box).stop().slideDown(200);
        var _textarea = $(box).find('textarea');
        if(_textarea.size() == 0) {
          _textarea = $(box).find('input:eq(0)');
        }
        _textarea.focus(); callback && callback('show');
      }else{
        
      }
    }else{
      $(box).stop().slideUp(200, function(){
        if(infopen.size()) infopen.hide();
        callback && callback('hide');
      });
      //box.style.display = 'none';
    }
  },*/
  // 初始化回复操作
  initReply: function() {
    this.comment_textarea = this.box.childModels['comment_textarea'][0];
    var mini_editor = this.comment_textarea.childModels['mini_editor'][0];
    var _textarea = $(mini_editor).find('textarea');
    if(_textarea.size() == 0) _textarea = $(mini_editor).find('input:eq(0)');
    var html = L('PUBLIC_RESAVE')+'@'+this.to_comment_uname+' ：';
    //清空输入框
    _textarea.val('');
    //_textarea.focus();
    _textarea.inputToEnd(html);
    _textarea.focus();
  },
  // 发表评论
  addComment:function(afterComment,obj) {
    var box = this.box;
    var _textarea = $(obj).parent().find('textarea');
    if(_textarea.size() == 0) {
      _textarea = $(obj).parent().find('input:eq(0)');
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
    if("undefined" != typeof(this.addComment) && (this.addComment == true)) {
      return false; //不要重复评论
    }
    var addcomment = this.addComment;
    var addToEnd = this.addToEnd;
    var url = request_url.comment_news.replace('{news_id}',this.row_id);
    var _this = this;
    obj.innerHTML = '回复中..';
    $.ajax({
        url: url,
        type: 'POST',
        data:{comment_content:content,reply_to_user_id:this.to_uid},
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(xml){},
        success:function(res){
          if (res.status == true) {
              if ( obj != undefined ){
                obj.innerHTML = '回复';
              }
              var html = '<div class="delComment_list">'+
                '<div class="comment_left">'+
                '<img src="'+API+'/storages/'+AVATAR+'" class="c_leftImg" />'+
                '</div>'+
                '<div class="comment_right">'+
                '<span class="del_ellen">'+NAME+'</span>'+
                '<span class="c_time">刚刚</span>'+
                '<i class="icon iconfont icon-gengduo-copy"></i>'+
                '<p>'+content+'<span class="del_huifu">'+
                '<a href="javascript:void(0)" data-args="editor=#comment&box=#comment_detail&to_uid='+res.data+'"'+
                'id="J-reply-comment">回复'+
                '</a></span></p>'+
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
                $('#J-reply-comment').on('click', function(){
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

