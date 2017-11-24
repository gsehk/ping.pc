@section('title')
    {{ $group->title }}
@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
<div class="left_container">

    <div class="group-cont">
        <div class="group-info">
            {{-- 圈子信息 --}}
            <div class="info" id="group_box">
                <div class="info-content">
                    <div class="content-left">
                        <img src="@if(isset($group->avatar->id))  {{ $routes['storage'].$group->avatar->id }} @else {{ asset('zhiyicx/plus-component-pc/images/default_picture.png') }} @endif" width="120px" height="120px"/>
                    </div>
                    <div class="content-right">
                        <div class="group-title">{{$group->title}}</div>
                        <div class="group-intro">{{$group->intro}}</div>
                        <div class="group-foot">
                            <div class="foot-count">
                                <span class="count">分享 <font class="mcolor">{{ $group->posts_count }}</font></span>
                                <span class="count">订阅 <font class="mcolor" id="join-count">{{ $group->members_count }}</font></span>
                            </div>

                            @if ($group->is_member)
                                <div class="group-join" gid="{{ $group->id }}"  status="1">已加入</div>
                            @else
                                <div class="group-join add-join" gid="{{ $group->id }}"  status="0">+加入</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 圈子动态发布 --}}
        @if(!empty($TS))
            <div class="feed_post group_post @if(!$group->is_member) hide @endif">
                {{--<input class="post_text" type="text" placeholder="有标题更吸引人 （最多三十字）" id="post_title">--}}
                <textarea class="post_textarea" placeholder="说说新鲜事" id="feed_content"></textarea>
                <div class="post_extra">
                <span class="font14" id="feed_pic">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                    图片
                </span>
                    <a href="javascript:;" class="post_button" onclick="post.createPost('{{$group->id}}')">分享</a>
                </div>
            </div>
        @endif
    </div>

    {{-- 动态列表 --}}
    <div class="feed_content">
        <div class="feed_menu">
            <a href="javascript:;" data-type="new" class="font16 @if ($type == 'new')selected @endif">最新</a>
            <a href="javascript:;" data-type="hot" class="font16 @if ($type == 'hot')selected @endif">热门</a>
            @if (!empty($TS))
                <a href="javascript:;" data-type="follow" class="font16 @if ($type == 'follow')selected @endif">关注的</a>
            @endif
        </div>
        <div id="feeds_list"></div>
    </div>


</div>

<div class="right_container">
    {{-- 热门圈子 --}}
    @include('pcview::widgets.hotgroups')
</div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.group.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.uploadify.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.picshow.js') }}"></script>
<script>
    $(function () {
        // 切换加入状态
        $('.group-foot').on('click', '.group-join', function(){
            checkLogin()
            var _this = this;
            var status = $(this).attr('status');
            var group_id = $(this).attr('gid');
            var joinCount = parseInt($('#join-count').text());
            group(status, group_id, function(){
                if (status == 1) {
                    $(_this).text('+加入');
                    $(_this).attr('status', 0);
                    $(_this).addClass('add-join');
                    $('#join-count').text(joinCount - 1);
                    $('.group_post').slideUp();
                } else {
                    $(_this).text('已加入');
                    $(_this).attr('status', 1);
                    $(_this).removeClass('add-join');
                    $('#join-count').text(joinCount + 1);
                    $('.group_post').slideDown();
                }
            });
        });

        // 切换帖子列表
        $('.feed_menu a').on('click', function() {
            var type = $(this).data('type');
            var group_id = "{{$group->id}}";
            // 清空数据
            $('#feeds_list').html('');

            // 加载相关微博
            var params = {
                type: type,
                group_id: group_id
            };
            scroll.init({
                container: '#feeds_list',
                loading: '.feed_content',
                url: '/group/postLists',
                params: params
            });

            // 修改样式
            $('.feed_menu a').removeClass('selected');
            $(this).addClass('selected');
        });

        // 获取帖子列表
        setTimeout(function() {
            var params = {
                type: $(this).data('type'),
                group_id: "{{$group->id}}"
            };
            scroll.init({
                container: '#feeds_list',
                loading: '.feed_content',
                url: '/group/postLists',
                params: params
            });
        }, 200);

        // 图片删除事件
        $(".feed_post").on("click", ".imgdel", function() {
            $(this).parent().remove();
            if ($('#file_upload_1-queue').find('.uploadify-queue-item').length == 0) {
                $('.uploadify-queue-add').remove();
                $('#file_upload_1-queue').hide();
            }
            if ($('#file_upload_1-queue').find('.uploadify-queue-item').length != 0  && $('.uploadify-queue-add').length == 0 ){
                var add = '<a class="feed_picture_span uploadify-queue-add"></a>'
                $('.uploadify-queue').append(add);
            }
        });

        // 发布微博
        var up = $('.post_extra').Huploadify({
            auto:true,
            multi:true,
            newUpload:true,
            buttonText:'',
            onUploadSuccess: post.afterUpload
        });
    });
</script>
@endsection