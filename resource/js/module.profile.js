

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
    this.setting.user_id = option.user_id || 0;             //  用户user_id
    this.setting.type = option.type || 'all';             //  微博分类
    this.setting.canload = option.canload || true;        // 是否能加载
    this.setting.page = option.page || 1;        // 页码
    if (option.type) {
        switch(option.type)
        {
            /* 全部动态 */
            case 'all':
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
            break;
            /* img type */
            case 'img':
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
            break;
            /* === */
          default:
                this.setting.url = request_url.get_user_feed.replace('{user_id}', option.user_id);
        }
    }

    weibo.bindScroll();

    if($(weibo.setting.container).length > 0 && this.setting.canload){
        $(weibo.setting.container).append(loadHtml);
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
                  $(weibo.setting.container).append(loadHtml);
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
    postArgs.type = weibo.setting.type;
    postArgs.page = weibo.setting.page;
    console.log(postArgs);
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
              } else {
                  $(weibo.setting.container).append(html);
                  $('.loading').remove();
              }
            } else {
              weibo.setting.canload = false;
              $('.loading').html('暂时没有更多可显示的内容哟~');
            }
        }
    });
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
              $('#digg'+feed_id).html('<a href="javascript:;" onclick="digg.delDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font>'+num+'</font>人喜欢</a>');
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
              $('#digg'+feed_id).html('<a href="javascript:;" onclick="digg.addDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>'+num+'</font>人喜欢</a>');
          }else{
              alert(res.message);
          }

          digg.digglock = 0;
        }
    });
  }
};





$(function(){
    // 关注
    $('.infR_time li').hover(function(){
        var type = $(this).attr('type');

        $(this).siblings().find('a').removeClass('hover');
        $(this).find('a').addClass('hover');

        $('.dyrBottom div').hide();
        $('#' + type).show();
    })

    $('.dyn_huan').on('click', function(){
        $('#cover').click();
    })

    $('#cover').on('change', function(e){
        var file = e.target.files[0];
        fileUpload(file, uploadPccover);
    });

    $('.dyn_top').hover(function(){
        $('.dyn_huan').show();
    },function(){
        $('.dyn_huan').hide();
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
            // comment.display(attrs);
        }else{
            $(attrs.box).hide();
        }
    });

    // 回复初始化
    $('#feeds-list').on('click', '.J-reply-comment', function(){
      var attrs = urlToObject($(this).data('args'));

      // comment.initReply(attrs);
    });

})

var uploadPccover = function(image, f, task_id){
    $.ajax({
        url: '/api/v1/users',
        type: 'PATCH',
        data: {cover_storage_task_id: task_id},
        dataType: 'json',
        success: function(res) {
            $('.dynTop_bg').attr('src', image.src);
        }
    });
}
