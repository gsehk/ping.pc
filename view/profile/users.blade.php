@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')

<div class="fan_cont">
    <ul class="fan_ul">
        @if($user_id && $user_id != $TS['id'])
        <li><a href="javascript:void;" data-type="1" @if($type == 1) class="a_border" @endif>TA的粉丝</a></li>
        <li><a href="javascript:void;" data-type="2" @if($type == 2) class="a_border" @endif>TA的关注</a></li>
        @else
        <li><a href="javascript:void;" data-type="1" @if($type == 1) class="a_border" @endif>粉丝</a></li>
        <li><a href="javascript:void;" data-type="2" @if($type == 2) class="a_border" @endif>关注</a></li>
        @endif
    </ul>
    
    <div id="follow-list"></div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script type="text/javascript">
    $(function(){
        // 关注
        $('.fan_care').on('click', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })
        $("img.lazy").lazyload({effect: "fadeIn"});
    })
    // 加载关联用户列表
    setTimeout(function() {
        weibo.init({
            container: '#follow-list',
            user_id:"{{ $user_id }}",
            loading: '.fan_cont',
            type: "{{ $type }}"
        });
    }, 300);    

    $('.fan_ul a').on('click', function(){
        var type = $(this).data('type');
        $('#follow-list').html('');
        weibo.init({container: '#follow-list',loading: '.fan_cont', user_id:"{{ $user_id }}",type: type});
        $('.fan_ul a').removeClass('a_border');
        $(this).addClass('a_border');
    });    

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('c_ccc');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('c_ccc');
        }
    }
</script>
@endsection