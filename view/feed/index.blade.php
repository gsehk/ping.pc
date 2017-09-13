@section('title')
动态
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
    <!-- 左侧菜单 -->
    @include('pcview::layouts.partials.leftmenu')

    <div class="feed_cont">

        <!-- 动态发布 -->
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

        <!-- 动态列表 -->
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


    <div class="right_container">
        @if (!empty($TS))
        <!-- 签到 -->
        @include('pcview::widgets.checkin')
        @endif

        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')

        <!-- 动态首页右侧广告位 -->
        @include('pcview::widgets.ads', ['space' => 'pc:feeds:right', 'type' => 1])


        <!-- 收入达人排行榜 -->
        @include('pcview::widgets.incomerank')
    </div>
@endsection

@section('scripts')

<script src="{{ asset('zhiyicx/plus-component-pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.uploadify.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js') }}"></script>
<script type="text/javascript">
// 加载微博
var params = {
    type: '{{ $type }}'
};

setTimeout(function() {
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/feeds/list',
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
            url: '/feeds/list',
            params: params
        });

        // 修改样式
        $('.feed_menu a').removeClass('selected');
        $(this).addClass('selected');
    });

    // 发布微博
    var up = $('.post_extra').Huploadify({
        auto:true,
        multi:true,
        newUpload:true,
        buttonText:'',
        onUploadSuccess: weibo.afterUpload
    });
});
</script>
@endsection
