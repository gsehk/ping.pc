@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')

<div class="fan_cont">
    <ul class="fan_ul">
        @if($user_id && $user_id != $TS['id'])
        <li><a href="{{ route('pc:followers', ['user_id'=>$user_id]) }}" @if($type == 1) class="a_border" @endif>TA的粉丝</a></li>
        <li><a href="{{ route('pc:followings', ['user_id'=>$user_id]) }}" @if($type == 2) class="a_border" @endif>TA的关注</a></li>
        @else
        <li><a href="{{ route('pc:followers') }}" @if($type == 1) class="a_border" @endif>粉丝</a></li>
        <li><a href="{{ route('pc:followings') }}" @if($type == 2) class="a_border" @endif>关注</a></li>
        @endif
    </ul>

    @if (!empty($users))
    <!-- 数据展示 -->
    @foreach ($users as $data)
    @if(($loop->iteration) % 3 == 1)
    <div class="fan_list">
    @endif
        <div class="fan_c">
            <div class="fanList_top">
                <div class="fan_header">
                    <a href="{{route('pc:mainpage',['user_id'=>$data['id']])}}">
                        <img src="{{ $data['avatar'] or $routes['resource'] . '/images/avatar.png' }} " class="head_img" alt="{{ $data['name'] }}"/>
                    </a>
                </div>
                <div class="fan_word">
                    <div>
                        <a href="{{route('pc:mainpage',['user_id'=>$data['id']])}}"><span class="fan_name">{{ $data['name'] or $data['phone'] }}</span></a>
                        @if ($data['follower'])
                        <span id="data" class="fan_care c_ccc" uid="{{ $data['id'] }}" status="1">已关注</span>
                        @else
                        <span id="data" class="fan_care" uid="{{ $data['id'] }}" status="0">+关注</span>
                        @endif
                    </div>
                    <div class="fan_subtitle">{{ $data['intro'] or '这家伙什么都没有写'}}</div>
                    <div class="fan_number">
                        <span class="fan_num">粉丝<span>{{ $data['extra']['followers_count'] or 0}}</span></span>
                        <span class="fan_careNum">关注<span>{{ $data['extra']['followings_count'] or 0}}</span></span>
                    </div>
                </div>
            </div>
        </div>
    @if(($loop->iteration) % 3 == 0)
    </div>
    @elseif($loop->iteration == count($users))
    </div>
    @endif
    @endforeach

    @else
    <!-- 缺省图 -->
    <div class="no_data_div">
        <div class="no_data"><img src="{{ $routes['resource']}}/images/pic_default_people.png"/><p>暂无相关内容</p></div>
    </div>
    @endif

</div>

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
        $("img.lazy").lazyload({effect: "fadeIn"});
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