@section('title') {{ $user->name }} 的个人主页@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/pc/css/profile.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/pc/css/feed.css') }}"/>
@endsection

@section('content')

{{-- 个人中心头部导航栏 --}}
@include('pcview::profile.navbar')

<div class="profile_body">
    <div class="left_container">
        {{-- 动态列表 --}}
        <div class="profile_content">
            <div class="profile_menu">
                <a href="javascript:;" class="active">全部</a>
            </div>
            <div id="content_list" class="profile_list"></div>
        </div>
    </div>

    <div class="right_container">
        {{-- 推荐用户 --}}
        @include('pcview::widgets.recusers')
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.profile.js') }}"></script>
<script src="{{ asset('assets/pc/js/module.weibo.js') }}"></script>v
<script src="{{ asset('assets/pc/js/module.picshow.js') }}"></script>
<script>
// 加载微博
var params = {
    type: 'users',
    cate: 1,
    isAjax: true,
    user: {{$user->id}}
};

scroll.init({
    container: '#content_list',
    loading: '.profile_content',
    url: '/profile',
    params: params
});

// 关注
$('#follow').click(function(){
    var _this = $(this);
    var status = $(this).attr('status');
    var user_id = "{{ $user->id }}";
    follow(status, user_id, _this, afterdata);
});

// 关注回调
var afterdata = function(target){
    if (target.attr('status') == 1) {
        target.find('span').text('关注');
        target.find('.icon').show();
        target.attr('status', 0);
        target.removeClass('followed');
    } else {
        target.find('.icon').hide();
        target.find('span').text('已关注');
        target.attr('status', 1);
        target.addClass('followed');
    }
}
</script>
@endsection