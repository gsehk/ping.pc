@php $route = Route::currentRouteName(); @endphp
<div class="dy_left">
    <ul>
        <li>
            <a href="{{Route('pc:feedAll')}}" class="fs-16 @if ($route == 'pc:feedAll') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy"></use></svg>全部动态
            </a>
        </li>
        <li>
            <a href="{{Route('pc:myFeed')}}" class="fs-16 @if ($route == 'pc:myFeed') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy"></use></svg>我的动态
            </a>
        </li>
        <!-- <li>
            <a href="{{Route('pc:related')}}" class="fs-16 @if ($route == 'pc:related') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy"></use></svg>与我相关
            </a>
            <span class="dy_num">33</span>
        </li> -->
        <li>
            <a href="{{Route('pc:myFans')}}" class="fs-16 @if ($route == 'pc:myFans') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use></svg>我的粉丝
            </a>
        </li>
        <li>
            <a href="{{Route('pc:following')}}" class="fs-16 @if ($route == 'pc:following') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use></svg>关注的人
            </a>
        </li>
        <li>
            <a href="{{Route('pc:rank')}}" class="fs-16 @if ($route == 'pc:rank') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy"></use></svg>排行榜
            </a>
        </li>
        <li>
            <a href="{{Route('pc:collection')}}" class="fs-16 @if ($route == 'pc:collection') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy"></use></svg>收藏的
            </a>
        </li>
        <li>
            <a href="{{Route('pc:account')}}" class="fs-16 @if ($route == 'pc:account') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy"></use></svg>设置
            </a>
        </li>

    </ul>
</div>