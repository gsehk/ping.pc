@php $route = Route::currentrouteName(); @endphp
<div class="dy_left">
    <ul>
        <li>
            <a href="{{route('pc:feed')}}" class="fs-16 @if ($route == 'pc:feed') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy"></use></svg>全部动态
            </a>
        </li>
        <li>
            <a href="{{route('pc:myFeed')}}" class="fs-16 @if ($route == 'pc:myFeed') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy"></use></svg>我的动态
            </a>
        </li>
        <!-- <li>
            <a href="{{route('pc:related')}}" class="fs-16 @if ($route == 'pc:related') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy"></use></svg>与我相关
            </a>
            <span class="dy_num">33</span>
        </li> -->
        <li>
            <a href="{{route('pc:users')}}" class="fs-16 @if ($route == 'pc:users') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use></svg>我的粉丝
            </a>
        </li>
        <li>
            <a href="{{route('pc:users', ['type'=>2])}}" class="fs-16 @if ($route == 'pc:users') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use></svg>关注的人
            </a>
        </li>
        <li>
            <a href="{{route('pc:rank')}}" class="fs-16 @if ($route == 'pc:rank') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy"></use></svg>排行榜
            </a>
        </li>
        <li>
            <a href="{{route('pc:collection')}}" class="fs-16 @if ($route == 'pc:collection') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy-copy"></use></svg>收藏的
            </a>
        </li>
        <li>
            <a href="{{route('pc:account')}}" class="fs-16 @if ($route == 'pc:account') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bofang1-copy-copy-copy"></use></svg>设置
            </a>
        </li>

    </ul>
</div>
