@section('title')
{{ $user->name }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 收藏列表 --}}
        <div class="profile_content">
            <div class="profile_menu J-menu">
                <a class="active" href="javascript:;" cid="1">@if ($TS->id == $user->id) 我加入的 @else TA加入的 @endif</a>
            </div>
            <div id="content_list" class="clearfix"></div>
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
scroll.init({
    container: '#content_list',
    loading: '.profile_content',
    url: '/profile/group',
    paramtype: 1,
    params: {limit: 15, user: {{$user->id}} }
});
</script>
@endsection