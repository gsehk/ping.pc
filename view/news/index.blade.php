@section('title')
资讯
@endsection

@extends('pcview::layouts.default')

@section('extra_class')news_container @endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
@endsection

@section('content')
<div class="news_slide">
    @include('pcview::widgets.ads', ['space' => 'pc:news:top', 'type' => 2])
</div>

<div class="news_main clearfix">
    <div class="news_body clearfix">
        <div class="news_left">
            <span class="more"></span>
            <ul class="news_cates_tab">
            <li data-cid="0" @if($cate_id == 0) class="selected" @endif>推荐</li>
            @foreach ($cates as $cate)
                <li data-cateid="{{ $cate['id'] }}" @if($cate_id == $cate['id']) class="selected" @endif>{{ $cate['name'] }}</li>
            @endforeach
            </ul>
            <div id="news_list">
            </div>
        </div>

        <div class="right_container">
            <div class="news_release_btn">
                <a href="javascript:;" id="news-release">
                    <span>
                        <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-feiji"></use></svg>投稿
                    </span>
                </a>
            </div>
            <!-- 近期热点 -->
            @include('pcview::widgets.hotnews')

            <!-- 广告位 -->
            @include('pcview::widgets.ads', ['space' => 'pc:news:right', 'type' => 1])
        </div>

    </div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.news.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/unslider.min.js') }}"></script>
<script type="text/javascript">
//加载资讯列表
var params = {
    cate_id: '{{ $cate_id }}',
    recommend: 1,
};

setTimeout(function() {
    scroll.init({
        container: '#news_list',
        loading: '#news_list',
        url: '/news/list',
        params: params
    });
}, 300);

(function(){
    // 分类展开
    var cateH = $('.news_cates_tab').innerHeight();
                $('.news_cates_tab').css('height', '86px');
    if (cateH > 86) {
        $('.more').show();
    } else {
        $('.more').hide();
    }

    $('.more').on('click', function(){
        if ($(this).hasClass('more_up')) {
            $('.news_cates_tab').animate({height: '86px'});
            $(this).removeClass('more_up');
        } else {
            $('.news_cates_tab').animate({height: cateH});
            $(this).addClass('more_up');
        }
    })

    // 轮播
    $('.unslider').unslider({delay:3000,dots: true});

    // 资讯分类操作菜单
    $('.news_cates_tab').on('click','li', function() {
        var cate_id = $(this).data('cateid');
        $('#news_list').html('');

        var params = {
            cate_id: cate_id
        };
        if (cate_id == undefined) {
            params = {
                cate_id: cate_id,
                recommend: 1,
            };
        }

        scroll.init({
            container: '#news_list',
            loading: '#news_list',
            url: '/news/list',
            params: params
        });

        $('.news_cates_tab li').removeClass('selected');
        $(this).addClass('selected');
    });

    // 图片懒加载
    $("img.lazy").lazyload({effect: "fadeIn"});

    // 投稿权限判断
    $('#news-release').on('click', function () {
        checkLogin();
        if (TS.BOOT['news:contribute'].verified && TS.USER.verified == null) {
            ly.confirm(formatConfirm('投稿提示', '成功通过平台认证的用户才能投稿，是否去认证？'), '去认证' , '', function(){
                window.location.href = "{{ route('pc:authenticate') }}";
            });
        } else {
            window.location.href = "{{ route('pc:newsrelease') }}";
        }

        return false;
    });
})()
</script>
@endsection
