@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/passport.css')}}"/>
@endsection

@section('content')
<div class="perfect_container">
    <div class="perfect_wrap">
        <div class="perfect_title">
            <p>选择标签</p>
            <span>标签为全局标签，选择合适的标签，系统可推荐你感兴趣的内容，方便找到相同身份或爱好的人，很重要哦！</span>
        </div>

        @foreach ($tags as $tag)
        <div class="perfect_row">
            <label>{{$tag->name}}</label>
            <ul class="perfect_label_list" id="J-tags">
            @foreach ($tag->tags as $item)
                <li class="
                @foreach ($user_tag as $t)
                    @if ($t->name == $item->name) active @endif
                @endforeach" data-id="{{$item->id}}">{{$item->name}}</li>
            @endforeach
            </ul>
        </div>
        @endforeach

        {{-- <div class="perfect_selected">
            <label>最多可选<span class="total">5</span>个标签，已选择<span class="cur_count">0</span>个</label>
            <ul class="perfect_selected_list">
                <li>建筑师<i class="icon close">x</i></li>
                <li>旅行家<i class="icon close">x</i></li>
                <li>运动达人<i class="icon close">x</i></li>
            </ul>
        </div> --}}

        <div class="perfect_btns">
            {{-- <a href="javascript:;" class="perfect_btn save" id="save">保存</a> --}}
            <a href="{{ route('pc:feeds') }}" class="perfect_btn skip" id="skip">跳过</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$('#J-tags li').on('click', function(e){
    var _this = $(this);
    var tag_id = $(this).data('id');
    var lenth = $('#J-tags li.active').length;
    if (_this.hasClass('active')) {
        $.ajax({
            url: '/api/v2/user/tags/'+tag_id,
            type: 'DELETE',
            dataType: 'json',
            error: function(response) {
                noticebox(response.responseJSON.message, 0);
            },
            success: function(res) {
                _this.removeClass('active');
            }
        });
    } else {
        if (lenth >= 5) {
            noticebox('个人标签最多选择５个', 0);
            return false;
        }
        $.ajax({
            url: '/api/v2/user/tags/'+tag_id,
            type: 'PUT',
            dataType: 'json',
            error: function(response) {
                console.log()
                noticebox(response.responseJSON.message, 0);
            },
            success: function(res) {
                _this.addClass('active');
            }
        });
    }
});
</script>
@endsection