@extends('pcview::layouts.default')

@section('content')
<div class="in_cont">
    <div class="inT_l" id="first_recommend_news">
        @if(isset($recommend))
            @foreach($recommend as $frv)
                @if($loop->first)
                <div class="inT_title">
                <a href="{{ route('pc:myFeed',['user_id'=>$frv['author']]) }}">
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
                <img src="{{$routes['storage']}}{{$sv->cover}}"></a>
              </li>
            @endforeach
        </ul>
        </div>
        @else 
            <img src="{{ $routes['resource'] }}/images/picture.png" />
        @endif
    </div>
    <div class="inT_r">
        <div class="inR_top">
            <img src="{{ $routes['resource'] }}/images/sign_bg.png" />
            <div class="inR_time">{{date('Y-m-d')}}</div>
            @if(empty($ischeck))
            <span class="inR_qd" onclick="checkin();" id="checkin">每日签到</span>
            <div class="inR_lk">立即签到，赚取<span> 5 </span>积分</div>
            @else 
            <span class="inR_qd">已签到</span>
            @endif
        </div>
        <div class="inR_bottom">
        <a href="{{ route('pc:users', ['type'=>2]) }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use>
                </svg>
                <span>关注的人</span>
            </div>
        </a>
        <a href="{{ route('pc:collection') }}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy-copy-copy-copy"></use>
                </svg>
                <span>收藏的</span>
            </div>
        </a>
        <a href="{{ route('pc:feed') }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy"></use>
                </svg>
                <span>全部动态</span>
            </div>
        </a>
        <a href="{{ route('pc:rank')}}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy-copy"></use>
                </svg>
                <span>排行榜</span>
            </div>
        </a>
        <a href="{{ route('pc:myFeed') }}" title="">
            <div class="inR_bottom_list border_r">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy"></use>
                </svg>
                <span>我的动态</span>
            </div>
        </a>
        <a href="{{ route('pc:account') }}" title="">
            <div class="inR_bottom_list">
                <svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-bofang1-copy-copy-copy"></use>
                </svg>
                <span>设置</span>
            </div>
        </a>
        </div>
    </div>
</div>

<div class="inf_cont clearfix">
    <div class="inf_main">
        <div class="inf_left">
            <ul class="news_cate_tab">
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
                            <a href="{{ route('pc:myFeed',['user_id'=>$u->info['id']]) }}"><img src="{{ $u->info['avatar'] }}" /></a>
                        </div>
                        <div class="i_right">
                            <span><a href="{{ route('pc:myFeed',['user_id'=>$u->info['id']]) }}">{{$u->user['name']}}</a></span>
                            <p>@if(!empty($u->info['intro'])) {{ $u->info['intro'] }} @else 这家伙很懒，什么都没留下 @endif</p>
                        </div>
                    </div>
                @endforeach
                @endif
                </div>
            </div>
            <div class="i_right_img"><img src="{{ $routes['resource'] }}/images/picture.png" /></div>
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time" id="j-recent-hot">
                    <li><a href="javascript:;" cid="1" class="week a_border">本周</a></li>
                    <li><a href="javascript:;" cid="2" class="meth">当月</a></li>
                    <li><a href="javascript:;" cid="3" class="moth">季度</a></li>
                </ul>
                <ul class="new_list" id="j-recent-hot-wrapp">
                    <div class='loading'><img src="{{ $routes['resource'] }}/images/loading.png" class='load'>加载中</div>
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
        window.location.href = "{{ route('pc:index', ['url'=>'/information/index']) }}"; 
        return;
    }
    var totalnum = {{$checkin['total_num'] or 0}} + 1;
    $.get('/home/checkin' , {} , function (res){
        if ( res ){
            var totalnum = res.data.score;
            $('#checkin').html('已签到');
            $('.totalnum').text(totalnum);
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

    $('.unslider').unslider({delay:3000});
        recent_hot(1);
        $('#j-recent-hot a').on('click', function(){
        var cid = $(this).attr('cid');
        recent_hot(cid);
        $('#j-recent-hot a').removeClass('a_border');
        $(this).addClass('a_border');
    });

    // 资讯分类操作菜单
    $('.news_cate_tab a').on('click', function() {
        var cid = $(this).data('cid');
        $('#news-list').html('');
        news.init({ container: '#news-list', cid: cid });
        $('.news_cate_tab a').removeClass('dy_59');
        $(this).addClass('dy_59');
    });

});
</script>
@endsection
