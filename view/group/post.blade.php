@section('title') {{ $post->title }} @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="feed_left">

            {{-- <div class="post-title">{{$post->title}}</div> --}}
            <dl class="user-box clearfix">
                <dt class="fl">
                    <a class="avatar_box" href="{{ route('pc:mine', $post->user_id) }}">
                    <img class="round" src="{{ getAvatar($post->user, 60) }}" width="60">
                    @if($post->user->verified)
                    <img class="role-icon" src="{{ $post->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                    @endif
                    </a>
                </dt>
                <dd class="fl ml20 body-box">
                    <span class="tcolor">{{ $post->user->name }}</span>
                    <div class="gcolor mt10">{{ getTime($post->created_at) }}</div>
                </dd>
            </dl>

            @if($post->images)
                <div class="detail_images" id="layer-photos-demo">
                    @foreach($post->images as $store)
                        <img data-original="{{ $routes['storage']}}{{$store['id'] }}?w=675&h=380" class="lazy img-responsive"/>
                    @endforeach
                </div>
            @endif

            <div class="detail_body">
                {!!$post->body!!}
            </div>

            <div class="detail_share">
                <span id="J-collect{{ $post->id }}" rel="{{ $post->collectors->count() }}" status="{{(int) $post->collected}}">
                    @if($post->collected)
                    <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                        <font class="cs">{{ $post->collectors->count() }}</font> 人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                        <font class="cs">{{ $post->collectors->count() }}</font> 人收藏
                    </a>
                    @endif
                </span>
                <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->likes_count}}" status="{{(int) $post->liked}}">
                    @if($post->liked)
                    <a class="act" href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like-copy"></use></svg>
                        <font>{{$post->likes_count}}</font> 人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg>
                        <font>{{$post->likes_count}}</font> 人喜欢
                    </a>
                    @endif
                </span>

                {{-- 第三方分享 --}}
                <div class="detail_third_share">
                    分享至：
                    @php
                        // 设置第三方分享图片，若未付费则为锁图。
                        if ($post->images->count() > 0) {
                            $share_pic = getenv('APP_URL') . '/api/v2/files/' . $post->images[0]['id'];
                        } else {
                            $share_pic = '';
                        }
                    @endphp
                    @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:feedread', ['post_id' => $post->id]), 'share_title' => addslashes($post->content), 'share_pic' => $share_pic])
                </div>
            </div>

            {{-- 评论 --}}
            @include('pcview::widgets.comments', ['id' => $post->id, 'group_id' => $post->group_id , 'comments_count' => $post->comments_count, 'comments_type' => 'group', 'loading' => '.feed_left', 'position' => 0])

        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="#">
                        <img src="{{ getAvatar($post->user, 60) }}" />
                    </a>
                </div>
                <div class="auth_info">
                    <div class="info_name">
                        <a href="#">{{ $post->user->name }}</a>
                    </div>
                    <p class="info_bio">{{ $post->user->bio or '暂无简介' }}</p>
                </div>
            </div>
            <ul class="auth_fans">
                <li><a href="{{ route('pc:follows', ['user_id' => $post->user->id, 'type' => 1]) }}">粉丝<span>{{ $post->user->extra->followers_count }}</span></a></li>
                <li><a href="{{ route('pc:follows', ['user_id' => $post->user->id, 'type' => 2]) }}">关注<span>{{ $post->user->extra->followings_count }}</span></a></li>
            </ul>
        </div>
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')
        <!-- 收入达人 -->
        @include('pcview::widgets.incomerank')

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.group.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/qrcode.js') }}"></script>
    <script type="text/javascript">
        layer.photos({
            photos: '#layer-photos-demo'
            ,anim: 5
            ,move: false
        });

        $(document).ready(function(){
            $("img.lazy").lazyload({effect: "fadeIn"});
        });


    </script>
@endsection
