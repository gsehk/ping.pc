@section('title') {{ $user->name }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 资讯列表 --}}
        <div class="profile_content">
            <div class="profile_menu J-menu">
            @if ($user->id == $TS->id)
                <a class="active" href="javascript:;" cid="0">已发布</a>
                <a href="javascript:;" cid="1">投稿中</a>
                <a href="javascript:;" cid="3">被驳回</a>
            @else
                <a class="active" href="javascript:;">TA的文章</a>
            @endif
            </div>
            <div id="content_list" class="profile_list"></div>
        </div>
    </div>

    <div class="right_container">
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.profile.js') }}"></script>
<script>
var params = {type: {{ $type }} };

@if ($user->id != $TS->id)
    params = { user: {{$user->id}} }
@endif

scroll.init({
    container: '#content_list',
    loading: '.profile_content',
    url: '/profile/news',
    params: params
});

$('.J-menu > a').on('click', function(){
	var type = $(this).attr('cid');

	$('#content_list').html('').hide();
	scroll.init({
        container: '#content_list',
        loading: '.profile_content',
        url: '/profile/news',
        params: {type: type }
    });

    $('.J-menu a').removeClass('active');
    $(this).addClass('active');
});
</script>
@endsection