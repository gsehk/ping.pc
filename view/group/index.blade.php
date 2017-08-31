@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="left_container">
    <div class="group_container">
        <div class="group_navbar">
            <a class="@if($type ==1)active @endif" href="{{ route('pc:group', 1) }}">全部圈子</a>
            <a class="@if($type ==2)active @endif" href="{{ route('pc:group', 2) }}">我关注的</a>
        </div>
        <div class="group_list clearfix" id="group_box">
        </div>
    </div>
</div>

<div class="right_container">
    @include('pcview::widgets.hotusers')
</div>
@endsection

@section('scripts')
<script>
setTimeout(function() {
    scroll.init({
        container: '#group_box',
        loading: '.group_container',
        url: '/group/list',
        params: {type: {{$type}}, limit: 10}
    });
}, 300);

$('#group_box').on('click', '.J-join', function(){
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