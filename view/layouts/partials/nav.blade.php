<div class="nav nav_border">
    <div class="nav_left">
        <a href="{{ route('pc:feeds') }}"><img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" /></a>
        <!-- <span class="nav_beta fs-16">beta</span> -->
    </div>
    <div class="nav_list clearfix">
        <ul>
            <li><a href="{{ route('pc:feeds') }}" @if(!empty($current) && $current == 'feeds') class="selected" @endif>动态</a></li>
            <!-- <li><a href="" class="fs-18">圈子</a></li> -->
            <!-- <li><a href="" class="fs-18">问答</a></li> -->
            <li><a href="{{ route('pc:news') }}" @if(!empty($current) && $current == 'news') class="selected" @endif>资讯</a></li>
            <li><a href="{{ route('pc:users') }}" @if(!empty($current) && $current == 'users') class="selected" @endif>找伙伴</a></li>
        </ul>

        <!-- <div class="nav_search">
            <input class="nav_input" type="text" placeholder="输入关键词搜索"/>
            <a class="nav_search_icon">
                <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
            </a>
        </div> -->
    </div>
    @if (!empty($TS))
    <div class="nav_right">
        <img src="{{ $TS['avatar'] or $routes['resource'] . '/images/avatar.png' }}" id="menu_toggle" alt="{{ $TS['name'] }}"/>
        <span class="fs-16 nav_name">{{$TS['name']}}</span>

        <div class="nav_menu">
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

