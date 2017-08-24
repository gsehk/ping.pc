@extends('pcview::layouts.default')

@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                <li><a href="{{ route('pc:users', ['type' => 1]) }}" @if($type == 1) class="selected" @endif>热门</a></li>
                <li><a href="{{ route('pc:users', ['type' => 2]) }}" @if($type == 2) class="selected" @endif>最新</a></li>
                <li><a href="{{ route('pc:users', ['type' => 3]) }}" @if($type == 3) class="selected" @endif>推荐</a></li>
            </ul>
            <div class="clearfix" id="user_list"></div>
        </div>
    </div>

    <div class="right_container">
        @include('pcview::widgets.hotusers')
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
        type: {{ $type }},
        limit: 10
    };
    setTimeout(function() {
        scroll.init({
            container: '#user_list',
            loading: '.user_container',
            url: '/users/getusers',
            params: params,
            loadtype: 1
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