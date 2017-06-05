@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')

<div class="fan_cont">
    @if($type == 3)
        <div class="visitor_div">近期有<span>{{$count}}</span>位Tser看过我</div>
    @elseif($type == 4)
    <ul class="fan_ul">
        <li><a href="{{ route('pc:users', ['type'=>4]) }}" class="a_border">推荐</a></li>
    </ul>
    @else
    <ul class="fan_ul">
        <li><a href="{{ route('pc:users', ['type'=>1, 'user_id'=>$user_id]) }}" @if($type == 1) class="a_border" @endif>@if($user_id != $TS['id'])TA的@endif粉丝</a></li>
        <li><a href="{{ route('pc:users', ['type'=>2, 'user_id'=>$user_id]) }}" @if($type == 2) class="a_border" @endif>@if($user_id != $TS['id'])TA的@endif关注</a></li>
    </ul>
    @endif

    @if (!empty($datas)) 
    <!-- 数据展示 -->
    @foreach ($datas as $data)
    @if(($loop->iteration) % 3 == 1)
    <div class="fan_list">
    @endif
        <div class="fan_c">
            <div class="fanList_top">
                <div class="fan_header">
                    <a href="{{route('pc:myFeed',['user_id'=>$data['user']['id']])}}">
                        <img src="{{ $data['user']['avatar']}} " class="head_img" alt="{{ $data['user']['name'] }}"/>
                    </a>
                </div>
                <div class="fan_word">
                    <div>
                        <a href="{{route('pc:myFeed',['user_id'=>$data['user']['id']])}}"><span class="fan_name">{{ $data['user']['name'] or $data['user']['phone'] }}</span></a>
                        @if ($data['my_follow_status'] == 1)
                        <span id="data" class="fan_care c_ccc" uid="{{ $data['user']['id'] }}" status="1">已关注</span>
                        @else
                        <span id="data" class="fan_care" uid="{{ $data['user']['id'] }}" status="0">+关注</span>
                        @endif
                    </div>
                    <div class="fan_subtitle">{{ $data['user']['intro'] or '这家伙什么都没有写'}}</div>
                    <div class="fan_number">
                        <span class="fan_num">粉丝<span>{{ $data['user']['data_count'] or 0}}</span></span>
                        <span class="fan_careNum">关注<span>{{ $data['user']['dataing_count'] or 0}}</span></span>
                    </div>
                </div>
            </div>
            <div class="fan_line"></div>
            <div class="fanList_bottom">
                @foreach ($data['storages'] as $storage)
                <a href="/home/{{ $storage['id'] }}/feed"> <img src="{{ $routes['storage'] }}{{ $storage['feed_storage_id'] }}" /></a>
                @endforeach

                @for ($i = 0; $i < (3 -count($data['storages'])); $i++)
                <a href="javascript:;"> <img src="{{ $routes['resource'] }}/images/default_picture.png" /></a>
                @endfor
            </div>
        </div>
    @if(($loop->iteration) % 3 == 0)
    </div>
    @elseif($loop->iteration == count($datas))
    </div>
    @endif
    @endforeach

    @else
    <!-- 缺省图 -->
    <div class="no_data_div">
        <!-- <div class="no_data"><img src="{{ $routes['resource']}}/images/pic_default_people.png"/><p>暂无相关内容</p></div> -->
        <div class="no_data"><img src="{{ $routes['resource']}}/jinronghu/pic_default_people.png"/><p>暂无相关内容</p></div>
    </div>
    @endif

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
            follow(status, user_id, _this, afterdata);
        })

    })

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