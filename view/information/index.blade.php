@extends('pcview::layouts.default')

@section('content')
<div class="in_cont">
    <div class="inT_l" id="first_recommend_news">
        @if(isset($recommend))
            @foreach($recommend as $frv)
                @if($loop->first)
                <div class="inT_title">
                <a href="{{ route('pc:mine',['user_id'=>$frv['author']]) }}">
                    <img src="{{ $frv['info']['avatar'] }}" />
                    <span>{{ $frv['info']['name'] }}</span>
                </a>
                </div>
                <a href="/information/read/{{$frv['id']}}">
                    <div class="inT_word">{{ $frv['title'] }}</div>
                </a>
                @endif
            @endforeach
        @endif
        <div class="inT_line"></div>
        <div class="inT_list" id="recommend_news">
            @if(isset($recommend))
                @foreach($recommend as $rv)
                    @if(!$loop->first)
                    <a href="/information/read/{{$rv['id']}}"><span class="inT_list">{{$rv['title']}}</span></a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <div class="inT_c">
        @if(isset($slide))
        <div class="unslider">
        <ul class="bannerList">
            @foreach($slide as $sv)
              <li>
              @if($sv->type == 'news')
              <a href="/information/read/{{$sv->data}}">
              @elseif($sv->type == 'url') 
              <a href="{{$sv->data}}" target="_blank">
              @endif
                <img src="{{$routes['storage']}}{{$sv->cover}}?w=580&h=414"></a>
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
    <div class="inT_r">
        {{-- <div class="inR_top">
            <img src="{{ $routes['resource'] }}/images/sign_bg.png" />
            <div class="inR_time">{{date('Y-m-d')}}</div>
            @if(empty($ischeck))
            <span class="inR_qd" onclick="checkin();" id="checkin">每日签到</span>
            <div class="inR_lk">立即签到，赚取<span> 5 </span>积分</div>
            @else 
            <span class="inR_qd">已签到</span>
            @endif
        </div> --}} 
        <div class="inR_bottom">
        <a href="{{ Route('pc:follow', ['type' => 2]) }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-attention"></use>
                </svg>
                <span>关注的人</span>
            </div>
        </a>
        <a href="{{ Route('pc:collect') }}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-collection"></use>
                </svg>
                <span>收藏的</span>
            </div>
        </a>
        <a href="{{ Route('pc:index') }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-dynamic"></use>
                </svg>
                <span>全部动态</span>
            </div>
        </a>
        {{-- <a href="{{ Route('pc:rank')}}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-rank"></use>
                </svg>
                <span>排行榜</span>
            </div>
        </a> --}}
        <a href="{{ Route('pc:mine') }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-mydynamic"></use>
                </svg>
                <span>我的动态</span>
            </div>
        </a>
        <a href="{{ Route('pc:account') }}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-setting"></use>
                </svg>
                <span>设置</span>
            </div>
        </a>
        </div>
    </div>
</div>

<div class="inf_cont clearfix">
    <div class="inf_main clearfix">
        <div class="inf_left">
            <span class="more"></span>
            <ul class="news_cate_tab">
            <a href="javascript:;" data-cid="0" @if($cid == 0) class="dy_59" @endif><li>全部</li></a>
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
                @if (isset($author))
                @foreach ($author as $u)
                    <div class="R_list">
                        <div class="i_left">
                            <a href="{{ route('pc:mine',['user_id'=>$u->info['id']]) }}"><img src="{{ $u->info['avatar'] }}" /></a>
                        </div>
                        <div class="i_right">
                            <span><a href="{{ route('pc:mine',['user_id'=>$u->info['id']]) }}">{{$u->user['name']}}</a></span>
                            <p>@if(!empty($u->info['intro'])) {{ $u->info['intro'] }} @else 这家伙很懒，什么都没留下 @endif</p>
                        </div>
                    </div>
                @endforeach
                @endif
                </div>
            </div>
            <div class="i_right_img">
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
        cid: "{{$cid}}"
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
