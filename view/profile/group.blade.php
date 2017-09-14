@section('title')
{{ $user->name }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}"/>
@endsection

@section('content')
<div class="profile_top">

    <div class="profile_top_cover">
        <img class="user_bg" src="{{ $user->bg or asset('zhiyicx/plus-component-pc/images/default_cover.png') }}"/>
    </div>

    @if ($user->id == $TS->id)
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img">
            <a href="{{ route('pc:mine', $user->id) }}">
                <img src="{{ $user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}"/>
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user">
                <a href="{{ route('pc:mine', $user->id) }}">{{ $user->name }}</a>
                <span>{{$user->location or '未知'}}</span>
            </div>
            <div class="profile_top_bio">{{ $user->bio or '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                @foreach ($user->tags as $tag)
                    <span>{{$tag->name}}</span>
                @endforeach
            </div>
            @if ($user->verified)
                <div class="profile_logo_icon">
                    <span><i class="tag_icon"></i>已认证：TS团队成员</span>
                </div>
            @endif

        </div>
    </div>

    {{-- navbar个人中心导航栏 --}}
    @include('pcview::profile.navbar')

</div>

<div class="profile_body">
    <div class="collect_box">
        {{-- 收藏列表 --}}
        <div class="feed_content">
            <div class="feed_menu J-menu">
                <a class="active" href="javascript:;" cid="1">@if ($TS->id == $user->id) 我加入的 @else TA加入的 @endif</a>
            </div>
            <div id="feeds_list" class="feed_box clearfix"></div>
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
        url: '/profile/group',
        params: {user: {{$user->id}} }
    });
}, 300);

$('#feeds_list').on('click', '.J-join', function(){

    if (MID == 0) {
        window.location.href = '/passport/login';
        return;
    }
    var _this = this;
    var status = $(this).attr('status');
    var group_id = $(this).attr('gid');
    group(status, group_id, function(){
        if (status == 1) {
            $(_this).text('+关注');
            $(_this).attr('status', 0);
            $(_this).removeClass('joined');
        } else {
            $(_this).text('已加入');
            $(_this).attr('status', 1);
            $(_this).addClass('joined');
        }
    });
});

</script>
@endsection