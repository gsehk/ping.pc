
var loadHtml = "<div class='loading'><img src='"+PUBLIC_URL +"/images/loading.png' class='load'>加载中</div>";
var request_url = {
    /* 获取文章列表 */
    get_news:'/information/getNewsList',
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
      console.log(args);
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
digg = {
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
    $.post('/api/v1/news/'+news_id+'/digg', function (res) {
      console.log(res);return false;
      if (res.status == true) {
        $digg = {};
        if (typeof $('#digg'+news_id)[0] === 'undefined') {
          $digg = $('#digg_'+news_id);
        } else {
          $digg = $('#digg'+news_id);
        }
        var num = $digg.attr('rel');
        num++;
        $digg.attr('rel', num);
        //$('#digg'+news_id).html('<a href="javascript:;" class="like-h" onclick="digg.delDigg('+news_id+')">已赞('+num+')</a>');
//        $('#digg_'+news_id).html('<a href="javascript:;" class="like-h" onclick="digg.delDigg('+news_id+')">已赞('+num+')</a>');
        $('#digg'+news_id).html('<a href="javascript:;" class="like-h digg-like-yes" title="取消赞" onclick="digg.delDigg('+news_id+')"><i class="digg-like"></i>('+num+')</a>');
        $('#digg_'+news_id).html('<a href="javascript:;" class="like-h digg-like-yes" title="取消赞" onclick="digg.delDigg('+news_id+')"><i class="digg-like"></i>('+num+')</a>');
      } else{
        ui.error(res.info);
      }
      digg.digglock = 0;
    }, 'json');
  },
  delDigg: function (news_id) {
    if (digg.digglock == 1) {
      return false;
    }
    digg.digglock = 1;
    $.post(U('widget/Digg/delDigg'), {news_id:news_id}, function(res) {
      if (res.status == 1) {
        $digg = {};
        if (typeof $('#digg'+news_id)[0] === 'undefined') {
          $digg = $('#digg_'+news_id);
        } else {
          $digg = $('#digg'+news_id);
        }
        var num = $digg.attr('rel');
        num--;
        $digg.attr('rel', num);
        var content;
        //if (num == 0) {
//          content = '<a href="javascript:;" onclick="digg.addDigg('+news_id+')">赞</a>';
//        } else {
//          content = '<a href="javascript:;" onclick="digg.addDigg('+news_id+')">赞('+num+')</a>';
//        }
                if (num == 0) {
          content = '<a href="javascript:;" onclick="digg.addDigg('+news_id+')" title="赞"><i class="digg-like"></i></a>';
        } else {
          content = '<a href="javascript:;" onclick="digg.addDigg('+news_id+')" title="赞"><i class="digg-like"></i>('+num+')</a>';
        }
        $('#digg'+news_id).html(content);
        $('#digg_'+news_id).html(content);
      } else {
        ui.error(res.info);
      }
      digg.digglock = 0;
    }, 'json');
  }
};
