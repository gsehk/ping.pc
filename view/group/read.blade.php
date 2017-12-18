@section('title') 圈子-{{ $group->name }} @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}"/>
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
<div class="p-readgroup">
    <div class="g-mn left_container">
        <div class="g-hd">
            <div class="m-snav f-cb">
               <nav class="m-crumb m-crumb-arr f-ib">
                   <ul class="f-cb s-fc4">
                       <li><a href="#">圈子</a></li>
                       <li><a href="#">{{$group->category->name}}</a></li>
                       <li>{{$group->name}}</li>
                   </ul>
               </nav>
               <div class="m-sch f-fr">
                    <input class="u-schipt" type="text" placeholder="输入关键词搜索">
                    <a class="u-schico" id="J-search" href="javascript:;"><svg class="icon s-fc"><use xlink:href="#icon-search"></use></svg></a>
               </div>
            </div>
            <div class="g-hd-ct">
                <div class="m-ct f-cb">
                    <div class="ct-left">
                        <img src="{{ $group->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png') }}" height="100%">
                        <span class="ct-cate">{{$group->category->name}}</span>
                    </div>
                    <div class="ct-right">
                        <div class="ct-tt">
                            {{$group->name}}
                            <span class="u-share">
                                <svg class="icon f-mr10"><use xlink:href="#icon-share"></use></svg>分享</span>
                        </div>
                        <p class="ct-intro">{{$group->summary}}</p>
                        <div class="ct-stat">
                            <span>帖子 <font class="s-fc">{{$group->posts_count}}</font></span>
                            <span>成员 <font class="s-fc" id="join-count-{{$group->id}}">{{$group->users_count}}</font></span>
                            <span>
                                <svg class="icon s-fc2 f-vatb"><use xlink:href="#icon-position"></use></svg>
                                <font class="s-fc">{{$group->location}}</font></span>
                                @if ($group->joined)
                                    <button
                                        class="joinbtn joined"
                                        id="{{$group->id}}"
                                        state="1"
                                        mode="{{$group->mode}}"
                                        money="{{$group->money}}"
                                        onclick="grouped.init(this);"
                                    >已加入</button>
                                @else
                                    <button
                                        class="joinbtn"
                                        id="{{$group->id}}"
                                        state="0"
                                        mode="{{$group->mode}}"
                                        money="{{$group->money}}"
                                        onclick="grouped.init(this);"
                                    >+加入</button>
                                @endif
                        </div>
                    </div>
                </div>
                <div class="m-tag">
                    <span>圈子标签</span>
                    @foreach ($group->tags as $tag)
                        <span class="u-tag">{{$tag->name}}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 动态列表 --}}
        <div class="feed_content">
            <div class="feed_menu">
                <a href="javascript:;" rel="latest_post" class="font16 @if($type=='latest_post')selected @endif">最新帖子</a>
                <a href="javascript:;" rel="latest_reply" class="font16 @if($type=='latest_reply')selected @endif">最新回复</a>
            </div>
            <div id="feeds_list"></div>
        </div>
    </div>

    <div class="g-side right_container">
        <div class="f-mb30">
            <a href="{{ route('pc:postcreate', $group->id) }}">
                <div class="u-btn">
                    <svg class="icon f-vatb" aria-hidden="true"><use xlink:href="#icon-writing"></use></svg>
                    <span>发 帖</span>
                </div>
            </a>
        </div>
        <div class="g-sidec s-bgc">
            <h3 class="u-tt">圈子公告</h3>
            <p class="u-ct f-tac">{{$group->notice or '暂无公告信息'}}</p>
        </div>
        <p class="u-more f-csp">
            <a class="f-db" href="{{ route('pc:groupnotice', ['group_id'=>$group->id]) }}">查看详细公告</a>
        </p>
        @if (in_array($group->joined->role, ['administrator', 'founder']))
            <div class="g-sidec f-csp f-mb30">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-setting"></use></svg>
                &nbsp;&nbsp;&nbsp;<a href="{{ route('pc:groupedit', ['group_id'=>$group->id]) }}">
                <span class="f-fs3">圈子管理</span></a>
            </div>
        @endif
        <div class="g-sidec">
            <h3 class="u-tt">圈子成员</h3>
            <dl class="qz-box">
                <dt><img class="avatar" src="{{$group->founder->user->avatar or asset('zhiyicx/plus-component-pc/images/pic_default_secret.png')}}"></dt>
                <dd>圈主：{{$group->founder->user->name}}</dd>
                <dd><span class="contact">联系圈主</span></dd>
            </dl>
            <ul class="cy-box">
                @foreach ($members as $member)
                    <li>
                        <a href="{{ route('pc:mine', $member->user_id) }}">
                            <img src="{{ $member->user->avatar or asset('zhiyicx/plus-component-pc/images/pic_default_secret.png') }}" width="50">
                            <p class="f-toe">{{$member->user->name}}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <p class="u-more f-csp">
            <a class="f-db" href="{{ route('pc:memberpage',['group_id'=>$group->id]) }}">更多圈子成员</a>
        </p>
        {{-- 热门圈子 --}}
        @include('pcview::widgets.hotgroups')
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.group.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.picshow.js') }}"></script>
<script>
    $(function () {
        // 初始帖子列表
        setTimeout(function() {
            scroll.init({
                container: '#feeds_list',
                loading: '.feed_content',
                url: '/group/postLists',
                paramtype: 1,
                params: {type:"{{$type}}", group_id:"{{$group->id}}", limit:15}
            });
        }, 200);

        // 切换帖子列表
        $('.feed_menu a').on('click', function() {
            $('#feeds_list').html('');
            scroll.init({
                container: '#feeds_list',
                loading: '.feed_content',
                url: '/group/postLists',
                paramtype: 1,
                params: {type:$(this).attr('rel'), group_id:"{{$group->id}}", limit:15}
            });

            $('.feed_menu a').removeClass('selected');
            $(this).addClass('selected');
        });
    });

    // 所有帖子搜索
    $('#J-search').on('click', function(){
        var key = $(this).prev('input').val();
        if (!key) {noticebox('搜索关键字不能为空', 0);return;}
        var params = {
            limit: 15,
            keyword: key,
            group_id: {{$group->id}},
        }
        $('#feeds_list').html('');

        scroll.init({
            container: '#feeds_list',
            loading: '.feed_content',
            url: '/group/postLists',
            paramtype: 1,
            params: params,
        });
    });
</script>
@endsection