@section('title')
动态
@endsection

@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
    <!-- left -->
    @include('pcview::layouts.partials.leftmenu')
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
                <a href="javascript:;" data-type="follow" class="fs-16 @if ($type == 'follow')selected @endif">关注的</a>
                @endif
                <a href="javascript:;" data-type="hot" class="fs-16 @if ($type == 'hot')selected @endif">热门</a>
                <a href="javascript:;" data-type="new" class="fs-16 @if ($type == 'new')selected @endif">最新</a>
            </div>
            <div id="feeds_list"></div>
        </div>
    </div>

    <!-- right -->
    <div class="right_container">
        <!-- checkin -->
        @include('pcview::widgets.checkin')

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
// 加载微博
var params = {
    type: '{{ $type }}'
};

setTimeout(function() {
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/feeds',
        params: params
    });
}, 300);

$(function(){

    // 切换分类
    $('.feed_menu a').on('click', function() {
        var type = $(this).data('type');
        // 清空数据
        $('#feeds_list').html('');

        // 加载相关微博
        var params = {
            type: type
        };
        scroll.init({
            container: '#feeds_list',
            loading: '.feed_content',
            url: '/feeds',
            params: params
        });

        // 修改样式
        $('.feed_menu a').removeClass('selected');
        $(this).addClass('selected');
    });



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
