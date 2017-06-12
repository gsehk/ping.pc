@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_cont">
    <!--top-->
    <div class="dyn_top">
        @if (!empty($user['cover']))
        <img src="{{ $routes['storage'] }}{{ $user['cover'] }}" class="dynTop_bg" />
        @else
        <img src="{{ $routes['resource'] }}/images/default_cover.png" class="dynTop_bg" />
        @endif
        @if ($user['id'] == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="dyn_huan" style="display:none">更换封面</span>
        @endif
        <div class="dyn_title"><a href="{{ route('pc:myFeed', ['user_id' => $user['id']]) }}">{{ $user['name'] }}</a></div>
        <div class="dynTop_cont">{{ $user['intro'] or '这家伙很懒，什么都没留下'}}</div>
        <div class="dyn_lImg">
            <!-- <a href="{{ route('pc:myFeed', ['user_id' => $user['id']]) }}"> -->
                <img src="{{ $user['avatar']}} " alt="{{ $user['name'] }}"/>
            <!-- </a> -->
        </div>
    </div>
    <div class="dynTop_b">
        @if (!empty($user['company']))
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
        @endif

        @if (!empty($user['province']))
        <span class="dyn_address"><i class="icon iconfont icon-site"></i>
            {{$user['province']}}
            @if(!empty($user['city'])){{'· '.$user['city']}}@endif
            @if(!empty($user['area'])){{'· '.$user['area']}}@endif
        </span>
        @endif
        @if(!empty($TS) && $TS['id'] == $user['id'])
        <a href="{{ route('pc:newsrelease') }}" class="dyn_contribute"><i class="icon iconfont icon-feiji tougao"></i>投稿</a>
        @endif
        @if(!empty($TS) && $TS['id'] != $user['id'])
        <div class="their_right">
            @if ($my_follow_status == 0)
            <div id="follow" status="0">+关注</div>
            @else
            <div id="follow" status="1" class="their_followed">已关注</div>
            @endif
            <!-- <div>私信</div> -->
        </div>
        @endif
    </div>
    <div class="dy_cont">
        <!--left-->
        <div class="dy_left"></div>
        <!--《center》-->
        <div class="dy_cCont dy_left_border">
            <div class="dy_center" style="width:664px;">
                <div class="dy_cen">
                    <div class="dy_tab">
                        <div class="artic_left">
                            <a href="javascript:;" data-type="all" class="fs-16 @if($type == 'all') dy_cen_333 @endif">动态</a>
                            <a href="javascript:;" data-type="img" class="fs-16 @if($type == 'img') dy_cen_333 @endif">图片</a>
                        </div>
                        <a href="{{ route('pc:article', ['user_id'=> $user['id']]) }}" class="artic_artic fs-16"><div>文章</div></a>
                    </div>
                    <div id="feeds-list"></div>
                </div>
            </div>
        </div>
        <!--<right>-->
        <div class="dy_right" style="margin-left:27px">
            <div class="dyrBottom">
                <ul class="infR_time">
                    <li type="followeds"><a class="hover" href="javascript:void(0);">粉丝</a></li>
                    <li type="followings"><a href="javascript:void(0);">关注</a></li>
                    <li type="visitors"><a href="javascript:void(0);">访客</a></li>
                </ul>

                <div id="followeds" class="userdiv" style="display:block">
                @if (!empty($followeds))
                <ul class="userlist">
                    @foreach ($followeds as $followed)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $followed['id']]) }}">
                            <img src="{{ $followed['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $followed['id']]) }}">{{ $followed['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if(count($followeds >= 6))<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>1, 'user_id'=>$followed['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif
                </div>

                <div id="followings" class="userdiv">
                @if (!empty($followings))
                <ul class="userlist">
                    @foreach ($followings as $following)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $following['id']]) }}">
                            <img src="{{ $following['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $following['id']]) }}">{{ $following['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if(count($followings >= 6))<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>2, 'user_id'=>$following['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif  
                </div>


                <div id="visitors" class="userdiv">
                @if (!empty($visitors))
                <ul class="userlist">
                    @foreach ($visitors as $visitor)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $visitor['id']]) }}">
                            <img src="{{ $visitor['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $visitor['id']]) }}">{{ $visitor['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if(count($visitors >= 6))<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>3, 'user_id'=>$visitor['id']]) }}">更多</a>@endif
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
<script src="{{ $routes['resource'] }}/js/md5-min.js"></script>
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script type="text/javascript">
    // 加载微博
    setTimeout(function() {
        weibo.init({
            container: '#feeds-list',
            user_id:"{{$user['id']}}",
            type: "{{$type}}"
        });
    }, 300);
    // 微博分类tab
    $('.artic_left a').on('click', function(){
        var type = $(this).data('type');
        $('#feeds-list').html('');
        weibo.init({container: '#feeds-list',user_id:"{{$user['id']}}",type: type});
        $('.artic_left a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = '{{$user['id']}}';
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