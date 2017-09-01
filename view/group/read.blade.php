@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="left_container">

    <div class="group-cont">
        <div class="group-info">
            <!-- 圈子信息 -->
            <div class="info" id="group_box">
                <div class="info-title">圈子 > {{$group->title}}</div>
                <div class="info-content">
                    <div class="content-left">
                        <img src="{{ $routes['storage'].$group->avatar->id }}" width="120px" height="120px"/>
                    </div>
                    <div class="content-right">
                        <div class="group-title">{{$group->title}}</div>
                        <div class="group-intro">{{$group->intro}}</div>
                        <div class="group-foot">
                            <div class="foot-count">
                                <span class="count">分享 <font class="mcolor">{{ $group->posts_count }}</font></span>
                                <span class="count">订阅 <font class="mcolor">{{ $group->members_count }}</font></span>
                            </div>

                            @if ($group->has_join)
                                <div class="group-join" gid="{{ $group->id }}"  status="1">已加入</div>
                            @else
                                <div class="group-join add-join" gid="{{ $group->id }}"  status="0">+加入</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 圈子动态发布 -->
        @if(!empty($TS))
            <div class="group_post">
                <textarea class="post_textarea" placeholder="说说新鲜事" id="group_content"></textarea>
                <div class="post_extra">
                <span class="fs-14" id="group_pic">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                    图片
                </span>
                    {{--<a href="javascript:;" class="post_button" onclick="weibo.postFeed()">分享</a>--}}
                </div>
            </div>
        @endif
    </div>

</div>

<div class="right_container">
    <!-- 热门圈子 -->
    @include('pcview::widgets.hotgroups')
</div>
@endsection

@section('scripts')
<script>

$('.group-foot').on('click', '.group-join', function(){

    if (MID == 0) {
        window.location.href = '/passport/login';
        return;
    }
    var _this = this;
    var status = $(this).attr('status');
    var group_id = $(this).attr('gid');
    group(status, group_id, function(){
        if (status == 1) {
            $(_this).text('+加入');
            $(_this).attr('status', 0);
            $(_this).addClass('add-join');
        } else {
            $(_this).text('已加入');
            $(_this).attr('status', 1);
            $(_this).removeClass('add-join');
        }
    });
});

</script>
@endsection