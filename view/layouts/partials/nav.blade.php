@php $route = Route::currentRouteName(); @endphp
<div class="nav nav_border">
    <div class="nav_left">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/logo.png') }}" class="nav_logo" />
        <span class="nav_beta fs-16">beta</span>
    </div>
    <div class="nav_list">
        <ul>
            <li><a href="{{Route('pc:feed')}}" class="fs-18 @if($route == 'pc:feed') c_59b6d7 @endif">动态</a></li>
            <li><a href="{{Route('pc:news')}}" class="fs-18 @if($route == 'pc:news') c_59b6d7 @endif">资讯</a></li>
        </ul>
    </div>

    <div class="nav_right">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/logo.png') }}" />
        <span class="fs-16 nav_name"></span>
    </div>

    @if (!empty($user))
    <div class="p_cont">
        <ul>
            <li>
                <a href="#"><i class="icon iconfont icon-shoucang-copy1"></i>我的收藏</a>
            </li>
            <li>
                <a href="#"><i class="icon iconfont icon-4-copy1"></i>我的积分/金额</a>
            </li>
            <li>
                <a href="#" class="p_cont_hover"><i class="icon iconfont icon-renzheng3-copy1"></i>去认证</a>
            </li>
            <li>
                <a href="#"><i class="icon iconfont icon-shezhi-copy"></i>设置</a>
            </li>
            <li style=" border-top: 1px solid #ededed; padding-top: 20px;">
                <a href="#">个人主页</a>
            </li>
            <li>
                <a href="#">退出</a>
            </li>
        </ul>
    </div>
    @endif
</div>

