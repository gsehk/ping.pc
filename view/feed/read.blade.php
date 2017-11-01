@section('title')
    动态详情
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
        <div class="feed_left">
            <dl class="user-box clearfix">
                <dt class="fl">
                    <a class="avatar_box" href="{{ route('pc:mine', $user['id']) }}">
                    <img class="round" src="{{ getAvatar($user, 60) }}" width="60">
                    @if($user->verified)
                    <img class="role-icon" src="{{ $user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                    @endif
                    </a>
                </dt>
                <dd class="fl ml20 body-box">
                    <span class="tcolor">{{ $user['name'] }}</span>
                    <div class="gcolor mt10">{{ getTime($feed['created_at']) }}</div>
                </dd>
                <dd class="fr mt20 relative">
                    <span class="options" onclick="options(this)">
                        <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                    </span>
                    <div class="options_div">
                        <div class="triangle"></div>
                        <ul>
                            @if(isset($TS->id) && $user->id == $TS->id)
                            <li>
                                <a href="javascript:;" onclick="weibo.pinneds({{$feed->id}});">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>申请置顶
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="weibo.delFeed({{$feed->id}}, 1);">
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                                </a>
                            </li>
                            @endif

                        </ul>
                    </div>
                </dd>
            </dl>

            @if(!empty($feed->images))
            <div class="detail_images" id="layer-photos-demo">
            @foreach($feed->images as $store)
                {{-- 计算图片高度 --}}
                @php
                    $size = explode('x', $store['size']);
                    $store_height = $size[0] > 675 ? 675 / $size[0] * $size[1] : $size[1];
                @endphp
                @if(isset($store['paid']) && !$store['paid'])
                    <img style="height:{{ $store_height }}px" data-original="{{ getImageUrl($store, '', '', false) }}" class="per_image lazy" onclick="weibo.payImage(this)" data-node="{{ $store['paid_node'] }}" data-amount="{{ $store['amount'] }}" data-file="{{ $store['file'] }}" />
                @else
                    <img data-original="{{ getImageUrl($store, '', '', false) }}" class="per_image lazy"/>
                @endif
            @endforeach
            </div>
            @endif

            <div class="detail_body">
                {!! formatContent($feed->feed_content) !!}
            </div>

            <div class="detail_share">
                <span id="J-collect{{ $feed->id }}" rel="{{ $feed->collect_count }}" status="{{(int) $feed->has_collect}}">
                    @if($feed->has_collect)
                    <a href="javascript:;" onclick="collected.init({{$feed->id}}, 'feeds', 0);" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $feed->collect_count }}</font> 人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collected.init({{$feed->id}}, 'feeds', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $feed->collect_count }}</font> 人收藏
                    </a>
                    @endif
                </span>
                <span id="J-likes{{$feed->id}}" rel="{{ $feed->like_count }}" status="{{(int) $feed->has_like}}">
                    @if($feed->has_like)
                    <a href="javascript:;" onclick="liked.init({{$feed->id}}, 'feeds', 0);" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font>{{ $feed->like_count }}</font> 人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="liked.init({{$feed->id}}, 'feeds', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font>{{ $feed->like_count }}</font> 人喜欢
                    </a>
                    @endif
                </span>

                {{-- 第三方分享 --}}
                <div class="detail_third_share">
                    分享至：
                    @php
                        //设置第三方分享图片，若未付费则为锁图。
                        if ($feed->images->count() > 0) {
                            $share_pic = getenv('APP_URL') . '/api/v2/files/' . $feed->images[0]['file'];
                        } else {
                            $share_pic = '';
                        }
                    @endphp
                    @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:feedread', ['feed_id' => $feed->id]), 'share_title' => $feed->feed_content, 'share_pic' => $share_pic])
                </div>

                {{-- 打賞 --}}
                @include('pcview::widgets.rewards' , ['rewards_data' => $feed->rewards, 'rewards_type' => 'feeds', 'rewards_id' => $feed->id, 'rewards_info' => $feed->reward])
            </div>

            {{-- 评论 --}}
            @include('pcview::widgets.comments', ['id' => $feed->id, 'comments_count' => $feed->feed_comment_count, 'comments_type' => 'feed', 'loading' => '.feed_left', 'position' => 0, 'top' => 1])

        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="{{ route('pc:mine', $user['id']) }}">
                        <img class="round" src="{{ getAvatar($user, 50) }}" width="50">
                        @if($user->verified)
                        <img class="role-icon" src="{{ $user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                        @endif
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
                <li><a href="{{ route('pc:follows', ['user_id' => $user['id'], 'type' => 1]) }}">粉丝<span>{{ $user['extra']['followers_count'] }}</span></a></li>
                <li><a href="{{ route('pc:follows', ['user_id' => $user['id'], 'type' => 2]) }}">关注<span>{{ $user['extra']['followings_count'] }}</span></a></li>
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
<script src="{{ asset('zhiyicx/plus-component-pc/js/qrcode.js') }}"></script>
<script type="text/javascript">
    layer.photos({
      photos: '#layer-photos-demo'
      ,anim: 5
      ,move: false
      ,img: '.per_image'
    });

    $(document).ready(function(){
        $("img.lazy").lazyload({effect: "fadeIn"});
    });
</script>
@endsection
