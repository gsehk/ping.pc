@extends('pcview::layouts.default')

@section('content')
<div class="in_cont">
    @if(!$slide->isEmpty())
        <div class="unslider">
            <ul class="bannerList">
                @foreach($slide as $sv)
                  <li>
                    @if($sv->type == 'news')
                        <a href="/news/read/{{$sv->data}}">
                    @elseif($sv->type == 'url') 
                        <a href="{{$sv->data}}" target="_blank">
                    @endif
                    <img src="{{$routes['storage']}}{{$sv->cover}}?h=414" width="100%" height="414"></a>
                  </li>
                @endforeach
            </ul>
        </div>
    @else 
        <a href="http://www.thinksns.com/zx/reader.php?id=94" target="_blank">
            <img src="{{ $routes['resource'] }}/images/ad_news.png" />
        </a>
    @endif
</div>

<div class="inf_cont clearfix">
    <div class="inf_main clearfix">
        <div class="inf_left">
            <span class="more"></span>
            <ul class="news_cate_tab">
            <a href="javascript:;" data-cid="0" class="dy_59"><li>全部</li></a>
            @if (isset($cate))
            @foreach ($cate as $post)
                @if ($loop->iteration < 10)
                <a href="javascript:;" data-cid="{{$post['id']}}" @if($cid == $post['id']) class="dy_59" @endif><li>{{ $post['name'] }}</li></a>
                @endif
            @endforeach
            @endif
            </ul>
            <div id="news-list">
            </div>
        </div>
        <div class="inf_right">
            <div class="infR_top">
                <div class="itop_autor">热门作者</div>
                <div id="j-author-hot-wrapp">
                    <div class="R_list hots_author">
                    @if ($author->isEmpty())
                        @foreach ($author as $user)
                            <div class="fl">
                                <a href="{{ Route('pc:mine',['user_id'=>$user->user['id']]) }}">
                                    <img src="{{ $user->user['avatar'] or $routes['resource'] . '/images/avatar.png' }}" />
                                </a>
                            </div>
                            <div class="i_right">
                                <span><a href="{{ route('pc:mine',['user_id'=>$user->user['id']]) }}">{{$user->user['name']}}</a></span>
                                <p class="bio">{{ $user->user['bio'] or '暂无简介信息' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="loading">暂无相关信息</div>
                    @endif
                    </div>
                </div>
            </div>
            <div class="news_ad">
            <a href="http://www.thinksns.com/zx/reader.php?id=94" target="_blank">
            <img src="{{ $routes['resource'] }}/images/ad_news.png" /></a>
            </div>
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time infR_time_1" id="j-recent-hot">
                    <li class="infR_time_3"><a href="javascript:;" cid="1" class="week a_border">一周</a></li>
                    <li class="infR_time_3"><a href="javascript:;" cid="2" class="meth">月度</a></li>
                    <li class="infR_time_3"><a href="javascript:;" cid="3" class="moth">季度</a></li>
                </ul>
                <ul class="new_list" id="j-recent-hot-wrapp">
                    <div class="list list1">
                        @if(!$hots['week']->isEmpty())
                        @foreach($hots['week'] as $week)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $week->id) }}">{{$week->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                    <div class="list list2">
                        @if(!$hots['month']->isEmpty())
                        @foreach($hots['month'] as $month)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $month->id) }}">{{$month->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                    <div class="list list3">
                        @if(!$hots['quarter']->isEmpty())
                        @foreach($hots['quarter'] as $quarter)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $quarter->id) }}">{{$quarter->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                </ul>
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