@extends('pcview::layouts.default')

@section('content')
<div class="in_cont">
    @if(!$ads->isEmpty())
        <div class="unslider">
            <ul class="bannerList">
                @foreach($ads as $ad)
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

<div class="inf_cont clearfix">
    <div class="inf_main clearfix">
        <div class="inf_left">
            <span class="more"></span>
            <ul class="news_cate_tab">
            <a href="javascript:;" data-cid="0" class="dy_59"><li>全部</li></a>
            @foreach ($cates as $cate)
                @if ($loop->iteration < 10)
                <a href="javascript:;" data-cid="{{ $cate['id'] }}" @if($cid == $cate['id']) class="dy_59" @endif><li>{{ $cate['name'] }}</li></a>
                @endif
            @endforeach
            </ul>
            <div id="news-list">
            </div>
        </div>
        <div class="inf_right">
            <div class="news_ad">
                <a href="http://www.thinksns.com/zx/reader.php?id=94" target="_blank">
                    <img src="{{ $routes['resource'] }}/images/ad_news.png" />
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.news.js"></script>
<script src="{{ $routes['resource'] }}/js/unslider.min.js"></script>
<script type="text/javascript">
var checkin = function(){
    if( MID == 0 ){
        return;
    }
    $.get('/home/checkin' , {} , function (res){
        if ( res ){
            $('#checkin').html('已签到');
            $('.inR_lk').remove();
            $('#checkin').addClass('dy_qiandao_sign');
        }
    });
};

setTimeout(function() {
    news.init({
        container: '#news-list',
        cid: 0
    });
}, 300);

$(document).ready(function(){
    var cateH = $('.news_cate_tab').innerHeight();
                $('.news_cate_tab').css('height', '86px');
    if (cateH > 86) {
        $('.more').show();
    } else {
        $('.more').hide();
    }
    // banner
    $('.unslider').unslider({delay:3000,dots: true});

    // 资讯分类操作菜单
    $('.news_cate_tab a').on('click', function() {
        var cid = $(this).data('cid');
        $('#news-list').html('');
        news.init({ container: '#news-list', cid: cid });
        $('.news_cate_tab a').removeClass('dy_59');
        $(this).addClass('dy_59');
    });
    $("img.lazy").lazyload({effect: "fadeIn"});

    $('.more').on('click', function(){
        if ($(this).hasClass('more_up')) {
            $('.news_cate_tab').animate({height: '86px'});
            $(this).removeClass('more_up');
        } else {
            $('.news_cate_tab').animate({height: cateH});
            $(this).addClass('more_up');
        }
    })
});
</script>
@endsection
