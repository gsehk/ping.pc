@section('title')
    动态详情
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
        <div class="feed_left">
            <div class="detail_user">
                <div class="detail_user_header">
                    <a href="#">
                        <img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" alt="">
                    </a>
                </div>
                <div class="detail_user_info">
                    <div class="detail_user_name"><a href="#">{{ $user['name'] }}</a></div>
                    <div class="detail_time">{{ getTime($feed['created_at']) }}</div>
                </div>
            </div>

            @if($feed->images)
            <div class="detail_images" id="layer-photos-demo">
            @foreach($feed->images as $store)
            <img data-original="{{ $routes['storage']}}{{$store['file'] }}?w=675&h=380" class="lazy img-responsive"/>
            @endforeach
            </div>
            @endif

            <div class="detail_body">
                {!!$feed->feed_content!!}
            </div>

            <div class="detail_share">
                <span id="collect{{ $feed['id'] }}" rel="{{ $feed['collect_count'] }}">
                    @if(!$feed['has_collect'])
                    <a href="javascript:;" onclick="collect.addCollect('{{ $feed['id'] }}', 'read')">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $feed['collect_count'] }}</font>人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collect.delCollect('{{ $feed['id'] }}', 'read');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $feed['collect_count'] }}</font>人收藏
                    </a>
                    @endif
                </span>
                <span id="digg{{ $feed['id'] }}" rel="{{ $feed['like_count'] }}">
                    @if(!$feed['has_like'])
                    <a href="javascript:;" onclick="digg.addDigg('{{ $feed['id'] }}', 'read');">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font class="ds">{{ $feed['like_count'] }}</font>人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="digg.delDigg('{{ $feed['id'] }}', 'read');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font class="ds">{{ $feed['like_count'] }}</font>人喜欢
                    </a>
                    @endif
                </span>
                <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                    分享至：
                    <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                    <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                </div>
                <div class="reward-box">
                    <p><button class="btn btn-warning btn-lg" id="J-reward-btn">打 赏</button></p>
                    <p class="reward-info tcolor">
                        <font color="#F76C6A">{{$feed['reward']['count']}} </font>次打赏，共
                        <font color="#F76C6A">{{$feed['reward']['amount'] or 0}} </font>元
                    </p>
                    <div class="reward-user">
                    @if (!$feed->rewards->isEmpty())
                    @foreach ($feed->rewards as $reward)
                        <div class="user-item">
                            <img class="lazy round" data-original="{{ $reward['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" alt="avatar" width="42" />
                            @if ($reward['user']['sex'])
                                <img class="sex-icon" src="{{ asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                            @endif
                        </div>
                    @endforeach
                        <span class="more-user"></span>
                    @endif
                    </div>
                </div>
            </div>
            <div class="detail_comment">
                <div class="comment_title"><span class="comment_count">{{$feed['feed_comment_count']}}</span>人评论</div>
                <div class="comment_box">
                    <textarea
                        class="comment_editor"
                        id="mini_editor"
                        placeholder="说点什么吧"
                        onkeyup="checkNums(this, 255, 'nums');"
                    ></textarea>
                    <div class="comment_tool">
                        <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                        <button
                            class="commnet_btn"
                            id="J-comment-feed"
                            data-args="to_uid=0&row_id={{$feed->id}}"
                            to_comment_id="0"
                            to_uid="0"
                        >评论</button>
                    </div>
                </div>
                <div class="comment_list" id="comment_box">

                </div>
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="#">
                        <img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png')}}?s=50" alt="">
                    </a>
                </div>
                <div class="auth_info">
                    <div class="info_name">
                        <a href="#">{{ $user['name'] }}</a>
                    </div>
                    <div class="info_bio">{{ $user['bio'] or '暂无简介' }}</div>
                </div>
            </div>
            <ul class="auth_fans">
                <li>粉丝<a href="javascript:;">{{ $user['followers'] }}</a></li>
                <li>关注<a href="javascript:;">{{ $user['followers'] }}</a></li>
            </ul>
        </div>
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')

        <!-- 收入达人排行榜 -->
        @include('pcview::widgets.incomerank')
    </div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.weibo.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/layer/layer.js') }}"></script>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5
  ,move: false
});

setTimeout(function() {
    scroll.init({
        container: '#comment_box',
        loading: '.feed_left',
        url: '/feeds/{{$feed->id}}/comments' ,
        canload: true
    });
}, 300);

$('#J-reward-btn').on('click', function(){
    var html = '<div class="reward-popups">'+
        '<p class="ucolor font14">选择打赏金额</p>'+
        '<div class="reward-sum">'+
            '<label class="opt tcolor" for="sum1">¥1.00'+
                '<input class="hide" id="sum1" type="radio" name="sum" value="1">'+
            '</label>'+
            '<label class="opt tcolor active" for="sum5">¥5.00'+
                '<input class="hide" id="sum5" type="radio" name="sum" value="5" checked>'+
            '</label>'+
            '<label class="opt tcolor" for="sum10">¥10.00'+
                '<input class="hide" id="sum10" type="radio" name="sum" value="10">'+
            '</label>'+
        '</div>'+
        '<p><input class="custom-sum" type="number" name="custom" placeholder="自定金额，必须是整数"></p>'+
        '<div class="reward-btn-box">'+
            '<button class="btn btn-default mr20" onclick="ly.close();">&nbsp;取 消&nbsp;</button>'+
            '<button class="btn btn-primary" onclick="rewarded.weibo(this, {{$feed->id}});">&nbsp;打 赏&nbsp;</button>'+
        '</div>'+
    '</div>';
    ly.loadHtml(html, '', '350px', '300px;');
    $('.reward-sum label').on('click', function(){
        $('.reward-sum label').removeClass('active');
        $(this).addClass('active');
    })
})

$(document).ready(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});
    $('#J-comment-feed').on('click', function(){
        if (MID == 0) {
            window.location.href = '/passport/login';
            return false;
        }
        var attrs = urlToObject($(this).data('args'));
        comment.addReadComment(attrs, this);
    });

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{$feed['share_desc']}}',
        'bdDesc' : '{{$feed['share_desc']}}',
        'bdUrl' : window.location.href
    });
});


</script>
@endsection
