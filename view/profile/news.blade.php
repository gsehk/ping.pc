@section('title')
{{ $user['name'] }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')
<div class="profile_top">

    <div class="profile_top_cover">
        <img class="user_bg" src="{{ $user['bg'] or URL::asset('zhiyicx/plus-component-pc/images/default_cover.png') }}"/>
    </div>

    @if ($user['id'] == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img">
            <a href="{{ route('pc:mine', $user['id']) }}">
                <img src="{{ $user['avatar'] or URL::asset('zhiyicx/plus-component-pc/images/avatar.png') }}"/>
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user">
                <a href="{{ route('pc:mine', ['user_id' => $user['id']]) }}">{{ $user['name'] }}</a>
                <span>{{$user['location'] or '未知'}}</span>
            </div>
            <div class="profile_top_bio">{{ $user['bio'] or '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                {{-- @foreach ($user['tags'] as $tag)
                    <span>{{$tag['name']}}</span>
                @endforeach --}}
            </div>
            @if ($user['verified'])
                <div class="profile_logo_icon">
                    <span><i class="tag_icon"></i>已认证：TS团队成员</span>
                </div>
            @endif

        </div>
    </div>

    <div class="profile_nav clearfix">
        <ul class="profile_nav_list clearfix">
            <li><a href="{{ route('pc:mine', $user['id']) }}">主页</a></li>
            <li class="active"><a href="{{ route('pc:profilenews') }}">资讯</a></li>
            <li><a href="{{ route('pc:profilecollect') }}">收藏</a></li>
        </ul>
        <a href="{{ route('pc:newsrelease') }}" class="profile_nav_btn">投稿</a>
    </div>

</div>

<div class="profile_body">
    <div class="left_box"></div>
    <div class="center_box">
        {{-- 动态列表 --}}
        <div class="feed_content">
        <div class="feed_menu J-menu">
            <a class="active" href="javascript:;" cid="0">已发布</a>
            <a href="javascript:;" cid="1">投稿中</a>
            <a href="javascript:;" cid="3">被驳回</a>
        </div>
            <div id="feeds_list" class="feed_box"></div>
        </div>
    </div>

    <div class="right_box">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.profile.js') }}"></script>
<script>
setTimeout(function() {
    scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/news',
        params: {type: {{ $type }} }
    });
}, 300);

$('.J-menu > a').on('click', function(){
	var type = $(this).attr('cid');

	$('#feeds_list').html('');
	scroll.init({
        container: '#feeds_list',
        loading: '.feed_content',
        url: '/profile/news',
        params: {type: type }
    });

    $('.J-menu a').removeClass('active');
    $(this).addClass('active');
});
</script>
@endsection