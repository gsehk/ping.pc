@section('title')
    {{ $post->title }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
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
                    <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
                    <img class="round" src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60" width="60">
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
                {!!$post->content!!}
            </div>

            <div class="detail_share">
                <span id="J-collect{{ $post->id }}" rel="{{ $post->collections }}" status="{{(int) $post->has_collection}}">
                    @if($post->has_collection)
                    <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $post->collections }}</font> 人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 0);">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $post->collections }}</font> 人收藏
                    </a>
                    @endif
                </span>
                <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->diggs}}" status="{{(int) $post->has_like}}">
                    @if($post->has_like)
                    <a class="act" href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font>{{$post->diggs}}</font> 人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="liked.init({{$post->id}}, 'group', 0)">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font>{{$post->diggs}}</font> 人喜欢
                    </a>
                    @endif
                </span>

                {{-- 第三方分享 --}}
                <div class="detail_third_share">
                    分享至：
                    @php
                        // 设置第三方分享图片，若未付费则为锁图。
                        if (!empty($post->images)) {
                            $share_pic = getenv('APP_URL') . '/api/v2/files/' . $post->images[0]['id'];
                        } else {
                            $share_pic = '';
                        }
                    @endphp
                    @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:feedread', ['post_id' => $post->id]), 'share_title' => addslashes($post->content), 'share_pic' => $share_pic])
                </div>
            </div>
            <div class="detail_comment">
                <div class="comment_title"><span class="comment_count cs{{$post->id}}"">{{$post['comments_count']}} </span>人评论</div>
                <div class="comment_box">
                    <textarea
                            class="comment_editor"
                            id="J-editor{{$post->id}}"
                            placeholder="说点什么吧"
                            onkeyup="checkNums(this, 255, 'nums');"
                    ></textarea>
                    <div class="comment_tool">
                        <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                        <button
                            class="btn btn-primary"
                            id="J-button{{$post->id}}"
                            onclick="post.addComment({{$post->id}}, {{$post->group_id}}, 0)"
                        > 评 论 </button>
                    </div>
                </div>
                <div class="comment_list J-commentbox" id="J-commentbox{{$post->id}}">

                </div>
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="right_about">
            <div class="info clearfix">
                <div class="auth_header">
                    <a href="#">
                        <img src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" />
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
                <li>粉丝<a href="javascript:;">{{ $post->user->extra->followers_count }}</a></li>
                <li>关注<a href="javascript:;">{{ $post->user->extra->followings_count }}</a></li>
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
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/qrcode.js') }}"></script>
    <script type="text/javascript">
        layer.photos({
            photos: '#layer-photos-demo'
            ,anim: 5
            ,move: false
        });

        setTimeout(function() {
            scroll.init({
                container: '.J-commentbox',
                loading: '.feed_left',
                url: '/group/{{$post['group_id']}}/post/{{$post['id']}}/comments' ,
                canload: true
            });
        }, 300);

        $(document).ready(function(){
            $("img.lazy").lazyload({effect: "fadeIn"});
        });


    </script>
@endsection
