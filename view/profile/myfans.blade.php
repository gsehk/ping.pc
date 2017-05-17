@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')


<div class="fan_cont">
    @if($type == 3)
        <div class="visitor_div">近期有<span>30</span>位Tser看过我</div>
    @else
    <ul class="fan_ul">
        <li><a href="{{Route('pc:myFans', ['type'=>1])}}" @if($type == 1) class="a_border" @endif>粉丝</a></li>
        <li><a href="{{Route('pc:myFans', ['type'=>2])}}" @if($type == 2) class="a_border" @endif>关注</a></li>
    </ul>
    @endif
    @foreach ($follows as $follow)
    @if(($loop->iteration) % 3 == 1)
    <div class="fan_list">
    @endif
        <div class="fan_c">
            <div class="fanList_top">
                <div class="fan_header">
                    @if (!empty($follow['avatar']))
                    <img src="{{ $routes['storage'] }}{{ $follow['avatar']}} " class="head_img" alt="{{ $follow['name'] }}"/>
                    @else
                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/avatar.png') }}" class="head_img" />
                    @endif
                </div>
                <div class="fan_word">
                    <div>
                        <span class="fan_name">{{ $follow['user']['name'] or $follow['user']['phone'] }}</span>
                        @if ($follow['my_follow_status'] == 1)
                        <span id="follow" class="fan_care c_ccc" uid="{{ $follow['user']['id'] }}" status="1">已关注</span>
                        @else
                        <span id="follow" class="fan_care" uid="{{ $follow['user']['id'] }}" status="0">+关注</span>
                        @endif
                    </div>
                    <div class="fan_subtitle">{{ $follow['user']['intro'] or '这家伙什么都没有写'}}</div>
                    <div class="fan_number">
                        <span class="fan_num">粉丝<span>{{ $follow['user']['follow_count'] or 0}}</span></span>
                        <span class="fan_careNum">关注<span>{{ $follow['user']['following_count'] or 0}}</span></span>
                    </div>
                </div>
            </div>
            <div class="fan_line"></div>
            <div class="fanList_bottom">
                @foreach ($follow['storages'] as $storage)
                <a href="javascript:;"> <img src="{{$routes['storage']}}{{$storage}}" /></a>
                @endforeach

                @for ($i = 0; $i < (3 -count($follow['storages'])); $i++)
                <a href="javascript:;"> <img src="{{$routes['resource']}}/images/default_picture.png" /></a>
                @endfor
            </div>
        </div>
    @if(($loop->iteration) % 3 == 0)
    </div>
    @elseif($loop->iteration == count($follows))
    </div>
    @endif
    @endforeach

</div>

<!-- 分页 -->
{{ $page }}

@endsection

@section('scripts')

<script type="text/javascript">
    $(function(){
        // 关注
        $('.fan_care').on('click', function(){
            var _this = $(this);
            var status = $(this).attr('status');
            var user_id = $(this).attr('uid');
            follow(status, user_id, _this, afterFollow);
        })

    })

    // 关注回调
    var afterFollow = function(target){
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