@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_cont">
    {{-- top --}}
    <div class="dyn_top">
        <img src="{{ $user->bg or $routes['resource'] . '/images/default_cover.png' }}" class="dynTop_bg" />
        @if ($user->id == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="dyn_huan">更换封面</span>
        @endif
        <div class="dyn_footer">
            <div class="dynTop_user">{{ $user->name }}
            </div>
            <div class="dynTop_cont">{{ $user->bio or '这家伙很懒，什么都没留下'}}</div>
            <div class="dyn_lImg">
                <a href="{{ route('pc:mine', ['user_id' => $user['id']]) }}">
                    <img src="{{ $user->avatar or $routes['resource'].'/images/avatar.png' }}" alt="{{ $user->name }}"/>
                </a> 
            </div>
        </div>
    </div>
    <div class="dynTop_b">
        {{-- @if (!empty($user['company']))
        <span class="dyn_zy"><i class="icon iconfont icon-gongsi"></i>
            {{ $user['company'] }}
        </span>
        @endif

        @if (!empty($user['year']))
        <span class="dyn_time"><i class="icon iconfont icon-shengri"></i>
            {{ $user['year'] }}
            @if (!empty($user['month']))
            {{ '.'.$user['month'] }}
            @endif
            
            @if (!empty($user['day']))
            {{ '.'.$user['day'] }}
            @endif

            @if (empty($user['sex']) || $user['sex'] == 3)
            <label>其他</label>
            @elseif($user['sex'] == 2)
            <label>女</label>
            @else
            <label>男</label>
            @endif
        </span>
        @endif --}}

        @if ($user->location)
        <span class="dyn_address"><i class="icon iconfont icon-site"></i>{{$user->location}}</span>
        @endif
        @if(!empty($TS) && $TS['id'] == $user->id)
        <a href="{{ Route('pc:newsrelease') }}" class="dyn_contribute"><i class="icon iconfont icon-feiji tougao"></i>投稿</a>
        @endif
        @if(!empty($TS) && $TS['id'] != $user->id)
        <div class="their_right">
            @if (!$user->following)
            <div id="follow" status="0">+关注</div>
            @else
            <div id="follow" status="1" class="their_followed">已关注</div>
            @endif
            {{-- <div>私信</div> --}}
        </div>
        @endif
    </div>
    <div class="dy_cont">
        <div class="dy_left"></div>
        <div class="dy_cCont dy_left_border">
            <div class="dy_center" style="width:664px;">
                <div class="dy_cen">
                    <div class="dy_tab">
                        <div class="artic_left">
                            <a href="javascript:;" data-type="all" class="fs-16 dy_cen_333">动态</a>
                        </div>
                        <a href="{{ route('pc:minearc', ['user_id'=> $user['id']]) }}" class="artic_artic fs-16">文章</a>
                    </div>
                    <div id="feeds-list"></div>
                </div>
            </div>
        </div>
        <div class="dy_right" style="margin-left:27px">
            <div class="dyrBottom">
                <ul class="infR_time">
                    <li type="followeds"><a class="hover" href="javascript:void(0);">粉丝</a></li>
                    <li type="followings"><a href="javascript:void(0);">关注</a></li>
                </ul>

                <div id="followeds" class="userdiv" style="display:block">
                @if (!$followers->isEmpty())
                <ul class="userlist">
                    @foreach ($followers as $follower)
                    <li>
                        <a href="{{ route('pc:mine', ['user_id' => $follower['id']]) }}">
                            <img src="{{ $follower['avatar'] or $routes['resource'] . '/images/avatar.png' }}" />
                        </a>
                        <span><a href="{{ route('pc:mine', ['user_id' => $follower['id']]) }}">{{ $follower['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if($followers->count() >= 6)<a class="dy_more fs-12" href="{{ route('pc:followers', ['user_id'=>$follower['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif
                </div>

                <div id="followings" class="userdiv">
                @if (!$followings->isEmpty())
                <ul class="userlist">
                    @foreach ($followings as $following)
                    <li>
                        <a href="{{ route('pc:mine', ['user_id' => $following['id']]) }}">
                            <img src="{{ $following['avatar'] or $routes['resource'] . '/images/avatar.png' }}" />
                        </a>
                        <span><a href="{{ route('pc:mine', ['user_id' => $following['id']]) }}">{{ $following['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if($followings->count() >= 6)<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>2, 'user_id'=>$following['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif  
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script src="{{ $routes['resource'] }}/js/jquery.uploadify.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script type="text/javascript">
    // 加载微博
    setTimeout(function() {
        weibo.init({
            container: '#feeds-list',
            user_id:"{{ $user->id }}",
            type: "all"
        });
    }, 300);

    // 微博分类tab
    $('.artic_left a').on('click', function(){
        var type = $(this).data('type');
        $('#feeds-list').html('');
        weibo.init({container: '#feeds-list',user_id:"{{ $user->id }}",type: type});
        $('.artic_left a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = "{{ $user->id }}";
        follow(status, user_id, _this, afterdata);
    })

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.removeClass('their_followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.addClass('their_followed');
        }
    }
</script>
@endsection