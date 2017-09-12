@section('title')
    找伙伴
@endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/user.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="user_container">
            <ul class="user_menu">
                <li><a type="1" href="javascript:;" @if($type == 1) class="selected" @endif>热门</a></li>
                <li><a type="2" href="javascript:;" @if($type == 2) class="selected" @endif>最新</a></li>
                <li><a type="3" href="javascript:;" @if($type == 3) class="selected" @endif>推荐</a></li>
            </ul>
            <div class="clearfix" id="user_list"></div>
        </div>
    </div>

    <div class="right_container">
        @include('pcview::widgets.hotusers')
    </div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.profile.js') }}"></script>
<script type="text/javascript">

    $(function(){
        var type = "{{ $type }}";
        // 关注
        $('#user_list').on('click', '.follow_btn', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterdata);
        })

        // 图片懒加载
        $("img.lazy").lazyload({effect: "fadeIn"});

        // 点击切换分类
        $('.user_menu a').click(function(){
            var type = $(this).attr('type');
            switchType(type);
            $(this).parents('ul').find('a').removeClass('selected');
            $(this).addClass('selected');
        })

        switchType(type);
    })

    // 切换类型加载数据
    var switchType = function(type){

        $('#user_list').html('');
        var params = {
            type: type,
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
    } 

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