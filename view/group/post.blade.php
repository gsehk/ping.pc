@section('title')
    {{ $post->title }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="feed_left">

            <div class="post-title">{{$post->title}}</div>
            <div class="detail_user">
                <div class="detail_user_header">
                    <a href="#">
                        <img src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png' }}" alt="">
                    </a>
                </div>
                <div class="detail_user_info">
                    <div class="detail_user_name"><a href="#">{{ $user['name'] }}</a></div>
                    <div class="detail_time">{{ getTime($post['created_at']) }}</div>
                </div>
            </div>

            @if($post->images)
                <div class="detail_images" id="layer-photos-demo">
                    @foreach($post->images as $store)
                        <img data-original="{{ $routes['storage']}}{{$store['file'] }}?w=675&h=380" class="lazy img-responsive"/>
                    @endforeach
                </div>
            @endif

            <div class="detail_body">
                {!!$post->content!!}
            </div>

            <div class="detail_share">
                <span id="collect{{ $post['id'] }}" rel="{{ $post['collections'] }}">
                    @if(!$post['has_collection'])
                        <a href="javascript:;" onclick="collect.addCollect('{{ $post['group_id'] }}', '{{ $post['id'] }}')">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $post['collections'] }}</font>收藏
                    </a>
                    @else
                        <a href="javascript:;" onclick="collect.delCollect('{{ $post['group_id'] }}', '{{ $post['id'] }}');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $post['collections'] }}</font>收藏
                    </a>
                    @endif
                </span>
                <span id="digg{{ $post['id'] }}" rel="{{ $post['diggs'] }}">
                    @if(!$post['has_like'])
                        <a href="javascript:;" onclick="digg.addDigg('{{ $post['group_id'] }}', '{{ $post['id'] }}');">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font class="ds">{{ $post['diggs'] }}</font>人喜欢
                    </a>
                    @else
                        <a href="javascript:;" onclick="digg.delDigg('{{ $post['group_id'] }}', '{{ $post['id'] }}');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font class="ds">{{ $post['diggs'] }}</font>人喜欢
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
            <div class="detail_comment">
                <div class="comment_title"><span class="comment_count">{{$post['comments_count']}}</span>人评论</div>
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
                                data-args="to_uid=0&group_id={{$post->group_id}}}&row_id={{$post->id}}"
                                to_comment_id="0"
                                to_uid="0"
                        >评论</button>
                    </div>
                </div>
                <div class="comment_list" id="comment_box" data-args="group_id={{$post->group_id}}">

                </div>
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
                    <p class="info_bio">{{ $user['bio'] or '暂无简介' }}</p>
                </div>
            </div>
            <ul class="auth_fans">
                <li>粉丝<a href="javascript:;">{{ $user['followers'] }}</a></li>
                <li>关注<a href="javascript:;">{{ $user['followers'] }}</a></li>
            </ul>
        </div>
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')
        <!-- 收入达人 -->
        @include('pcview::widgets.incomepeople')

    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.group.js') }}"></script>
    <script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
    <script src="{{ URL::asset('zhiyicx/plus-component-pc/layer/layer.js') }}"></script>
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
                url: '/group/' + {{$post['group_id']}} + '/post/' + {{$post['id']}} + '/comments' ,
                canload: true
            });
        }, 300);

        $(document).ready(function(){
            $("img.lazy").lazyload({effect: "fadeIn"});

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

                comment.addReadComment(attrs, this);
            });

            bdshare.addConfig('share', {
                "tag" : "share_feedlist",
                'bdText' : '{{$post['share_desc']}}',
                'bdDesc' : '{{$post['share_desc']}}',
                'bdUrl' : window.location.href,
                'bdPic' : '{{ $routes['resource'] }}/images/default_cover.png'
            });
        });


    </script>
@endsection
