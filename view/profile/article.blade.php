@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_cont">
    {{-- top --}}
    <div class="dyn_top">
        @if (!empty($user['cover']))
        <img src="{{ $routes['storage'] }}{{ $user['cover'] }}" class="dynTop_bg" />
        @else
        <img src="{{ $routes['resource'] }}/images/default_cover.png" class="dynTop_bg" />
        @endif
        @if ($user['id'] == $TS['id'])
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="dyn_huan">更换封面</span>
        @endif
        <div class="dyn_footer">
            <div class="dynTop_user">{{ $user['name'] }}
            @if($user['user_verified'])
                <img width="35" src="{{ $routes['resource'] }}/images/vip_icon.svg">
            @endif
            </div>
            <div class="dynTop_cont">{{ $user['intro'] or '这家伙很懒，什么都没留下'}}</div>
            <div class="dyn_lImg">
                {{-- <a href="{{ route('pc:mainpage', ['user_id' => $user['id']]) }}"> --}}
                    <img src="{{ $user['avatar']}} " alt="{{ $user['name'] }}"/>
                {{-- </a> --}}
            </div>
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

        @if (!empty($user['location']))
        <span class="dyn_address"><i class="icon iconfont icon-site"></i>{{$user['location']}}</span>
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
            {{-- <div>私信</div> --}}
        </div>
        @endif
    </div>
    <div class="dy_cont">
        {{-- left --}}
        <div class="dy_left"></div>
        {{-- 《center》 --}}
        <div class="dy_cCont dy_left_border">
            <div class="dy_center" style="width:664px;">
                <div class="dy_cen">
                    <div class="top-menu-left">
                        <div class="artic_left border_left">
                            <a href="{{ route('pc:mainpage', ['type'=>'all', 'user_id'=> $user['id']]) }}" class="fs-16 @if($type == 'all') dy_cen_333 @endif">动态</a>
                            <a href="{{ route('pc:mainpage', ['type'=>'img', 'user_id'=> $user['id']]) }}" class="fs-16 @if($type == 'img') dy_cen_333 @endif">图片</a>
                        </div>
                        <a href="{{ route('pc:article', ['user_id'=> $user['id']]) }}" class="artic_artic fs-16 border_left_no dy_cen_333"><div>文章</div></a>
                    </div>
                    @if(!empty($TS) && $TS['id'] == $user['id'])
                    <div class="article_state">
                        <a href="javascript:;" data-state="0" class="fs-14 @if($type == 0) dy_cen_333 @endif">已发布</a>
                        <a href="javascript:;" data-state="1" class="fs-14 @if($type == 1) dy_cen_333 @endif">投稿中</a>
                        <a href="javascript:;" data-state="3" class="fs-14 @if($type == 3) dy_cen_333 @endif">被驳回</a>
                        <a href="javascript:;" data-state="2" class="fs-14 @if($type == 2) dy_cen_333 @endif">草稿</a>
                    </div>
                    @endif
                    <div id="article-list">
                    </div>
                </div>
            </div>
        </div>
        {{-- <right> --}}
        <div class="dy_right" style="margin-left:27px">
            <div class="dyrBottom">
                <ul class="infR_time">
                    <li type="followeds"><a class="hover" href="javascript:void(0)">粉丝</a></li>
                    <li type="followings"><a href="javascript:void(0)">关注</a></li>
                    {{-- <li type="visitors"><a href="javascript:void(0)">访客</a></li> --}}
                </ul>

                <div id="followeds" class="userdiv" style="display:block">
                @if (!$followeds->isEmpty())
                <ul class="userlist">
                    @foreach ($followeds as $followed)
                    <li>
                        <a href="{{ route('pc:mainpage', ['user_id' => $followed['id']]) }}">
                            <img src="{{ $followed['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:mainpage', ['user_id' => $followed['id']]) }}">{{ $followed['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if($followeds->count() >= 6)<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>1, 'user_id'=>$followed['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif
                </div>

                <div id="followings" class="userdiv">
                @if (!$followings->isEmpty())
                <ul class="userlist">
                    @foreach ($followings as $following)
                    <li>
                        <a href="{{ route('pc:mainpage', ['user_id' => $following['id']]) }}">
                            <img src="{{ $following['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:mainpage', ['user_id' => $following['id']]) }}">{{ $following['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                @if($followings->count() >= 6)<a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>2, 'user_id'=>$following['id']]) }}">更多</a>@endif
                @else
                <p class="nodata">暂无内容</p>
                @endif  
                </div>


                {{-- <div id="visitors" class="userdiv">
                @if (!empty($visitors))
                <ul class="userlist">
                    @foreach ($visitors as $visitor)
                    <li>
                        <a href="{{ route('pc:mainpage', ['user_id' => $visitor['id']]) }}">
                            <img src="{{ $visitor['avatar'] }}" />
                        </a>
                        <span><a href="{{ route('pc:mainpage', ['user_id' => $visitor['id']]) }}">{{ $visitor['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4, 'user_id'=>$user['id']]) }}">更多</a>
                @else
                <p class="nodata">暂无内容</p>
                @endif  
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/md5-min.js"></script>
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script type="text/javascript">
// 加载文章
setTimeout(function() {
    news.init({
        container: '#article-list',
        user_id:"{{$user['id']}}",
        type:"{{$type}}"
    });
}, 300);
// 文章分类tab
$('.article_state a').on('click', function(){
    var type = $(this).data('state');
    $('#article-list').html('');
    news.init({container: '#article-list',user_id:"{{$user['id']}}",type:type});
    $('.article_state a').removeClass('dy_cen_333');
    $(this).addClass('dy_cen_333');
});

</script>
@endsection