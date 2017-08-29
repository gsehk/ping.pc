@section('title')
{{ $user['name'] }}的个人主页
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')
<div class="profile_top">

    <!-- cover -->
    <div class="profile_top_cover">
        <img src="{{ $user->bg or $routes['resource'] . '/images/default_cover.png' }}"/>
    </div>
    <!-- /cover -->

    @if ($user->id == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img">
            <a href="{{ route('pc:mine', ['user_id' => $user['id']]) }}">
                <img src="{{ $user->avatar or $routes['resource'].'/images/avatar.png' }}" alt="{{ $user->name }}"/>
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user"><a href="#">{{ $user->name }}</a><span>成都 · 高新</span></div>
            <div class="profile_top_bio">{{ $user->bio or '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                <span>前端工程师</span>
                <span>前端</span>
                <span>前端架构师</span>
            </div>
            <div class="profile_logo_icon">
                <span><i class="tag_icon"></i>已认证：TS团队成员</span>
                <span><i class="tag_icon"></i>已认证：TS团队成员</span>
            </div>
        </div>
    </div>

    <!-- nav -->
    <div class="profile_nav clearfix">
        <ul class="profile_nav_list clearfix">
            <li class="active"><a href="#">主页</a></li>
            <li><a href="#">圈子</a></li>
            <li><a href="#">问答</a></li>
            <li><a href="#">草稿箱</a></li>
        </ul>

        <!-- btn -->
        <a href="#" class="profile_nav_btn">投稿</a>
        <!-- /btn -->

    </div>
    <!-- /nav -->
</div>

<div class="profile_body">
    <div class="profile_left">
    </div>

    <div class="right_container">
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script src="{{ $routes['resource'] }}/js/jquery.uploadify.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script type="text/javascript">
    // 加载微博
    setTimeout(function() {
        weibo.init({
            container: '#feeds-list',
            user_id:"{{ $user->id }}",
            type: "all"
        });
    }, 300);

    // 微博分类tab
    $('.artic_left a').on('click', function(){
        var type = $(this).data('type');
        $('#feeds-list').html('');
        weibo.init({container: '#feeds-list',user_id:"{{ $user->id }}",type: type});
        $('.artic_left a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = "{{ $user->id }}";
        follow(status, user_id, _this, afterdata);
    })

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('their_followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('their_followed');
        }
    }
</script>
@endsection