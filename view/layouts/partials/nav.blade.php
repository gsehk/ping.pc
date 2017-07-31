@php $route = route::currentRouteName(); @endphp
<div class="nav nav_border">
    <div class="nav_left">
        <a href="{{ route('pc:index') }}"><img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" /></a>
        <!-- <span class="nav_beta fs-16">beta</span> -->
    </div>
    <div class="nav_list">
        <ul>
            <li><a href="{{ route('pc:index')}}" class="fs-18 @if($route == 'pc:index') c_59b6d7 @endif">动态</a></li>
            <li><a href="{{ route('pc:news')}}" class="fs-18 @if($route == 'pc:news') c_59b6d7 @endif">资讯</a></li>
        </ul>
    </div>
    @if (!empty($TS))
    <div class="nav_right">
        <img src="{{ $TS['avatar'] or $routes['resource'] . '/images/avatar.png' }}" id="menu_toggle" alt="{{ $TS['name'] }}"/>
        <span class="fs-16 nav_name">{{$TS['name']}}</span>

        <div class="p_cont">
            <div class="hover_cover clearfix">
            </div>
            <ul>
                <li>
                    <a href="{{ route('pc:collect') }}"><i class="icon iconfont icon-shoucang-copy1"></i>我的收藏</a>
                </li>
                {{-- <li>
                    <a href="{{ route('pc:credit') }}"><i class="icon iconfont icon-4-copy1"></i>我的积分/金额</a>
                </li>
                <li>
                    <a href="{{ route('pc:account', ['page'=>'account-auth']) }}"><i class="icon iconfont icon-renzheng3-copy1"></i>去认证</a>
                </li> --}}
                <li>
                    <a href="{{ route('pc:account') }}"><i class="icon iconfont icon-shezhi-copy"></i>设置</a>
                </li>
                <li style="border-top: 1px solid #ededed; padding-top: 20px;">
                    <a href="{{ route('pc:mine')}}">个人主页</a>
                </li>
                <li style="padding-bottom: 10px;">
                    <a href="javascript:void(0)" onclick="logout()">退出</a>
                </li>
            </ul>
            <img src="{{ $routes['resource'] }}/images/triangle.png" class="triangle" />
        </div>
    </div>
    @else
    <div class="nav_right">
        <a class="nava" href="{{ route('pc:register') }}">注册</a>
        <a class="nava" href="{{ route('pc:login') }}">登录</a>
    </div>
    @endif
</div>

