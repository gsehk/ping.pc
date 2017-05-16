
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
        $(weibo.setting.container).append("<div class='loading'><img src='"+PUBLIC_URL +"/images/loading.png' class='load'>加载中</div>");
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
                  $(weibo.setting.container).append("<div class='loading'><img src='"+PUBLIC_URL +"/images/loading.png' class='load'>加载中</div>");
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
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
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
 * 上传后操作
 * @return void
 */
weibo.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" src="' + image.src + '" tid="' + task_id + '"/>';
    var del = '<span class="imgdel"><i class="icon iconfont icon-close"></i></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
}

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
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        success: function(res) {
            console.log(res);
        }

    })

}

// 图片删除时间绑定
$(function(){
    $(".dy_cTop").on("click", ".imgdel", function(){
        $(this).parent().remove();
        if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
            $('#file_upload_1-queue').hide();
        }
    });
});
