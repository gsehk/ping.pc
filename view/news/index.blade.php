@section('title')
资讯
@endsection

@extends('pcview::layouts.default')

@section('extra_class')news_container @endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
@endsection

@section('content')
<div class="news_slide">
    @if(!$ads['top']->isEmpty())
        <div class="unslider">
            <ul class="bannerList">
                @foreach($ads['top'] as $ad)
                  <li>
                    <a href="{{ $ad['link'] }}">
                        <img src="{{ $ad['image'] }}" width="100%" height="414">
                    </a>
                  </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="news_main clearfix">
    <div class="news_body clearfix">
        <div class="news_left">
            <span class="more"></span>
            <ul class="news_cates_tab">
            <a href="javascript:;" data-cid="0" class="selected"><li>全部</li></a>
            @foreach ($cates as $cate)
                @if ($loop->iteration < 10)
                <a href="javascript:;" data-cateid="{{ $cate['id'] }}" @if($cate_id == $cate['id']) class="selected" @endif><li>{{ $cate['name'] }}</li></a>
                @endif
            @endforeach
            </ul>
            <div id="news_list">
            </div>
        </div>

        <div class="right_container">

            <!-- 资讯右侧广告 -->
            @if(!$ads['right']->isEmpty())
            @foreach($ads['right'] as $ad)
            <div class="news_ad">
                <a href="{{ $ad['link'] }}" target="_blank">
                    <img src="{{ $ad['image'] }}" />
                </a>
            </div>
            @endforeach
            @endif

        </div>

    </div>
</div>
@endsection


@section('scripts')
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.news.js') }}"></script>
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/unslider.min.js') }}"></script>
<script type="text/javascript">

setTimeout(function() {
    scroll.init({
        container: '#news_list',
        cid: 0
    });
}, 300);
//加载资讯列表
var params = {
    cate_id: '{{ $cate_id }}'
};

setTimeout(function() {
    scroll.init({
        container: '#news_list',
        loading: '#news_list',
        url: '/news/list',
        params: params
    });
}, 300);


$(document).ready(function(){
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

    // banner
    $('.unslider').unslider({delay:3000,dots: true});

    // 资讯分类操作菜单
    $('.news_cates_tab a').on('click', function() {
        var cate_id = $(this).data('cateid');
        $('#news_list').html('');

        var params = {
            cate_id: cate_id
        };
        scroll.init({
            container: '#news_list',
            loading: '#news_list',
            url: '/news/list',
            params: params
        });

        $('.news_cates_tab a').removeClass('selected');
        $(this).addClass('selected');
    });

    // 图片懒加载
    $("img.lazy").lazyload({effect: "fadeIn"});

});
</script>
@endsection
