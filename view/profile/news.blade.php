@section('title') {{ $user->name }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

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
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.profile.js') }}"></script>
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