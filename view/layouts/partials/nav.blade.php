@php $route = route::currentRouteName(); @endphp
<div class="nav nav_border">
    <div class="nav_left">
        <a href="{{ route('pc:feed') }}"><img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" /></a>
        <span class="nav_beta fs-16">beta</span>
    </div>
    <div class="nav_list">
        <ul>
            <li><a href="{{ route('pc:feed')}}" class="fs-18 @if($route == 'pc:feed') c_59b6d7 @endif">动态</a></li>
            <li><a href="{{ route('pc:news')}}" class="fs-18 @if($route == 'pc:news') c_59b6d7 @endif">资讯</a></li>
        </ul>
    </div>
    @if (!empty($TS))
    <div class="nav_right">
        <img src="{{ $TS['avatar']}}" id="menu_toggle" alt="{{ $TS['name'] }}"/>
        <span class="fs-16 nav_name">{{$TS['name']}}</span>
    </div>
    <div class="p_cont">
        <ul>
            <li>
                <a href="{{ route('pc:collection') }}"><i class="icon iconfont icon-shoucang-copy1"></i>我的收藏</a>
            </li>
            <li>
                <a href="{{ route('pc:credit') }}"><i class="icon iconfont icon-4-copy1"></i>我的积分/金额</a>
            </li>
            <li>
                <a href="{{ route('pc:account', ['page'=>'account-auth']) }}"><i class="icon iconfont icon-renzheng3-copy1"></i>去认证</a>
            </li>
            <li>
                <a href="{{ route('pc:account') }}"><i class="icon iconfont icon-shezhi-copy"></i>设置</a>
            </li>
            <li style=" border-top: 1px solid #ededed; padding-top: 20px;">
                <a href="{{ route('pc:myFeed')}}">个人主页</a>
            </li>
            <li>
                <a href="{{ route('pc:logout')}}">退出</a>
            </li>
        </ul>
        <img src="{{ $routes['resource'] }}/images/triangle.png" class="triangle" />
    </div>
    @else
    <div class="nav_right">
        <a class="nava" href="{{ route('pc:register') }}">注册</a>
        <a class="nava" href="{{ route('pc:index') }}">登录</a>
    </div>
    @endif
</div>

