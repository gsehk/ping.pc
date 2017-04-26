@php $route = Route::currentRouteName(); @endphp
<div class="nav nav_border">
    <div class="nav_left">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/logo.png') }}" class="nav_logo" />
        <span class="nav_beta fs-16">beta</span>
    </div>
    <div class="nav_list">
        <ul>
            <li><a href="#" class="fs-18 @if($route == 'pc:feed') c_59b6d7 @endif">动态</a></li>
            <li><a href="{{Route('pc:news')}}" class="fs-18 @if($route == 'pc:news') c_59b6d7 @endif">资讯</a></li>
        </ul>
    </div>

    <div class="nav_right">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/logo.png') }}" />
        <span class="fs-16 nav_name">大师大师</span>
    </div>
</div>

