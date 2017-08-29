@section('title')
    动态详情
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
    <div class="feed_left">
        
        <div class="detail_user">
            <div class="detail_user_header">
                <a href="#">
                    <img src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png' }}" alt="">
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
                <a href="javascript:;" onclick="collect.addCollect('{{ $feed['id'] }}')">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                    <font class="cs">{{ $feed['collect_count'] }}</font>收藏
                </a>
                @else 
                <a href="javascript:;" onclick="collect.delCollect('{{ $feed['id'] }}');" class="act">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                    <font class="cs">{{ $feed['collect_count'] }}</font>收藏
                </a>
                @endif
            </span>
            <span id="digg{{ $feed['id'] }}" rel="{{ $feed['digg_count'] }}">
                @if(!$feed['collect_count'])
                <a href="javascript:;" onclick="digg.addDigg('{{ $feed['id'] }}');">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                    <font class="ds">{{ $feed['digg_count'] }}</font>人喜欢
                </a>
                @else 
                <a href="javascript:;" onclick="digg.delDigg('{{ $feed['id'] }}');" class="act">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                    <font class="ds">{{ $feed['digg_count'] }}</font>人喜欢
                </a>
                @endif
            </span>
            <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                分享至：
                <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
            </div>
        </div>

    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="#">
                        <img src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png'}}" alt="">
                    </a>
                </div>
                <div class="auth_info">
                    <div class="info_name">
                        <a href="#">{{ $user['name'] }}</a>
                    </div>
                    <p class="info_bio">{{ $user['bio'] }}</p>
                </div>
            </div>
            <ul class="auth_fans">
                <li>粉丝<a href="javascript:;">{{ $user['followers'] }}</a></li>
                <li>关注<a href="javascript:;">{{ $user['followers'] }}</a></li>
            </ul>
        </div>
        <!-- 推荐用户 -->

        @include('pcview::widgets.recusers')
    </div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/js/module.bdshare.js"></script>
<script src="{{ $routes['resource'] }}/layer/layer.js"></script>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
  ,move: false
}); 

$(document).ready(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});
    comment.init({row_id:'{{$feed->id}}', canload:true});

    $('#j-recent-hot a').hover(function(){
        $('.list').hide();
        $('.list'+$(this).attr('cid')).show();
        $('#j-recent-hot a').removeClass('a_border');
        $(this).addClass('a_border');
    });

    $('#J-comment-feed').on('click', function(){
        if (MID == 0) {
            window.location.href = '/passport/index';
            return false;
        }
        var attrs = urlToObject($(this).data('args'));
        attrs.to_uid = $(this).attr('to_uid');
        attrs.addToEnd = $(this).attr('addtoend');
        attrs.to_comment_id = $(this).attr('to_comment_id');
        comment.init(attrs);

        var _this = this;
        var after = function(){
            $(_this).attr('to_uid','0');
            $(_this).attr('to_comment_id','0');
        }
        comment.addReadComment(after, this);
    });

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{$feed['share_desc']}}',
        'bdDesc' : '{{$feed['share_desc']}}',
        'bdUrl' : window.location.href,
        'bdPic' : '{{ $routes['resource'] }}/images/default_cover.png'
    });
});


</script>
@endsection
