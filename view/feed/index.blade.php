@section('title')
动态
@endsection

@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
    <!-- left -->
    @component('pcview::layouts.partials.leftmenu')
    @endcomponent
    <!-- middle -->
    <div class="feed_cont">
        @if(!empty($TS))
        <div class="feed_post">
            <textarea class="post_textarea" placeholder="说说新鲜事" id="feed_content"></textarea>
            <div class="post_extra">
                <span class="fs-14" id="feed_pic">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                    图片
                </span>
                <a href="javascript:;" class="post_button" onclick="weibo.postFeed()">分享</a>
            </div>
        </div>
        @endif
        <div class="feed_content">
            <div class="feed_menu">
                @if (!empty($TS))
                <a href="javascript:;" data-type="follow" class="fs-16 @if ($type == 'follow') dy_cen_333 @endif">关注的</a>
                @endif
                <a href="javascript:;" data-type="hot" class="fs-16 @if ($type == 'hot') dy_cen_333 @endif">热门</a>
                <a href="javascript:;" data-type="new" class="fs-16 @if ($type == 'new') dy_cen_333 @endif">最新</a>
            </div>
            <div id="feeds-list"></div>
        </div>
    </div>

    <!-- right -->
    <div class="feed_right">
        <!-- checkin -->
        @include('pcview::widgets.check')

        <!-- recommend users -->
        @include('pcview::widgets.recusers')
    </div>
@endsection

@section('scripts')

<link href="{{ $routes['resource'] }}/css/feed.css" rel="stylesheet">
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/js/jquery.uploadify.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script type="text/javascript">

var checkin = function(){
  if( MID == 0 ){
    return;
  }
  var totalnum = {{$checkin['total_num'] or 0}} + 1;
  $.get('/home/checkin' , {} , function (res){
    if ( res ){
      var totalnum = res.data.score;
      $('#checkin').html('<svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">'+res.data.con_num+'</font>天</span>');
      $('.totalnum').text(totalnum);
    }
  });
};

// 加载微博
setTimeout(function() {
    weibo.init({
        container: '#feeds-list',
        loading: '.feed_content',
        type: "{{$type}}"
    });
}, 300);

$(function(){
    // 发布微博
    var loadgif = RESOURCE_URL + '/images/loading.png';
    var up = $('.dy_company').Huploadify({
        auto:true,
        multi:true,
        newUpload:true,
        buttonText:''
    });
});
</script>
@endsection
