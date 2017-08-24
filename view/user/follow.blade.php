@extends('pcview::layouts.default')

@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                @if($user_id && $user_id != $TS['id'])
                <li><a href="javascript:void;" @if($type == 1) class="selected" @endif>TA的粉丝</a></li>
                <li><a href="javascript:void;" @if($type == 2) class="selected" @endif>TA的关注</a></li>
                @else
                <li><a href="{{ route('pc:followers', ['user_id' => $user_id]) }}" @if($type == 1) class="selected" @endif>粉丝</a></li>
                <li><a href="{{ route('pc:followings', ['user_id' => $user_id]) }}" @if($type == 2) class="selected" @endif>关注</a></li>
                @endif
            </ul>
            <div class="clearfix" id="user_list"></div>
        </div>
    </div>
@endsection

@section('scripts')
<link href="{{ $routes['resource'] }}/css/user.css" rel="stylesheet">
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script type="text/javascript">
    $(function(){
        // 关注
        $('#user_list').on('click', '.follow_btn', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })
        $("img.lazy").lazyload({effect: "fadeIn"});
    })

    // 加载用户列表
    var params = {
        user_id: {{ $user_id }},
        type: {{ $type }}
    };
    setTimeout(function() {
        scroll.init({
            limit: 9,
            container: '#user_list',
            loading: '.user_container',
            url: '/users/getfollows',
            params: params
        });
    }, 300);    

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('followed');
        }
    }
</script>
@endsection