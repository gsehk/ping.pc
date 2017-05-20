@extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="in_cont">
        <div class="inT_l" id="first_recommend_news">
            @if($recommend)
                @foreach($recommend as $frv)
                    @if($loop->first)
                    <div class="inT_title">
                        <img src="{{ $routes['storage'] }}{{ $frv['user']['avatar'] }}" />
                        <span>{{ $frv['user']['name'] }}</span>
                    </div>
                    <div class="inT_word">{{ $frv['title'] }}</div>
                    @endif
                @endforeach
            @endif
            <div class="inT_line"></div>
            <div class="inT_list" id="recommend_news">
                @if($recommend)
                    @foreach($recommend as $rv)
                        @if(!$loop->first)
                        <span>{{$rv['title']}}</span>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="inT_c">
            <img src="{{ $routes['resource'] }}/images/picture.png" />
        </div>
        <div class="inT_r">
            <div class="inR_top">
                <img src="{{ $routes['resource'] }}/images/picture.png" />
                <div class="inR_time">2017-4-10</div>
                <span class="inR_qd">每日签到</span>
                <div class="inR_lk">立即签到，赚取<span>5</span>积分</div>
            </div>
            <div class="inR_bottom">
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use>
                    </svg>
                    <span>关注的人</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy"></use>
                    </svg>
                    <span>收藏的</span>
                </div>
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy"></use>
                    </svg>
                    <span>全部动态</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy"></use>
                    </svg>
                    <span>排行榜</span>
                </div>
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy"></use>
                    </svg>
                    <span>我的动态</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy"></use>
                    </svg>
                    <span>设置</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="inf_cont">
    <div class="inf_main">
        <div class="inf_left">
            <ul>
            @foreach ($cate as $post)
                @if ($loop->iteration < 10)
                <a href="{{ routes('pc:news', ['cid'=>$post['id']]) }}"><li @if($cid == $post['id']) class="dy_59" @endif>{{ $post['name'] }}</li></a>
                @endif
            @endforeach
            </ul>
            <div id="news-list">
            </div>
        </div>
        <div class="inf_right">
            <div class="infR_top">
                <div class="itop_autor">热门作者</div>
                <div id="j-author-hot-wrapp">
                @foreach ($author as $user)
                    <div class="R_list">
                        <div class="i_left">
                            <img src="{{ $routes['resource'] }}/images/cicle.png" />
                        </div>
                        <div class="i_right">
                            <span>{{$user['user']['name']}}</span>
                            <p>{{$user['user']['intro']}}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <div class="i_right_img"><img src="{{ $routes['resource'] }}/images/picture.png" /></div>
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time" id="j-recent-hot">
                    <li><a href="javascript:;" class="week a_border">本周</a></li>
                    <li><a href="javascript:;" class="meth">当月</a></li>
                    <li><a href="javascript:;" class="moth">季度</a></li>
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
<script type="text/javascript">
setTimeout(function() {
    news.init({
        container: '#news-list',
        cid: "{{$cid}}"
    });
}, 300);

$(document).ready(function(){
  recent_hot(1);
  $('#j-recent-hot .week').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(1);
  });
  $('#j-recent-hot .meth').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(2);
  });
  $('#j-recent-hot .moth').on('click', function(){
    $('#j-recent-hot a').removeClass('a_border');
    $(this).addClass('a_border');
    recent_hot(3);
  });
});
</script>
@endsection
