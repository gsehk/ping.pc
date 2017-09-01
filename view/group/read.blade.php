@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}">
@endsection

@section('content')
<div class="left_container">

</div>

<div class="right_container">
    <!-- 热门圈子 -->
</div>
@endsection

@section('scripts')
<script>
setTimeout(function() {
    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        url: '/group/list',
        params: {type: 1, limit: 10}
    });
}, 300);

$('#group_box').on('click', '.J-join', function(){

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


// 切换分类
$('.group_navbar a').on('click', function() {
    var type = $(this).data('type');
    // 清空数据
    $('#group_box').html('');

    // 加载相关微博
    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        url: '/group/list',
        params: {type: type, limit: 10}
    });

    // 修改样式
    $('.group_navbar a').removeClass('active');
    $(this).addClass('active');
});

</script>
@endsection