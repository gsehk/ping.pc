@php $route = route::currentrouteName(); @endphp
<div class="dy_left">
    <ul>
        <li>
            <a href="{{ route('pc:index') }}" class="fs-16 @if ($route == 'pc:index') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-dynamic"></use></svg>全部动态
            </a>
        </li>
        <li>
            <a href="{{ route('pc:mine') }}" class="fs-16 @if ($route == 'pc:mine') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-mydynamic"></use></svg>我的动态
            </a>
        </li>
        <li>
            <a href="{{ route('pc:followers') }}" class="fs-16 @if ($route == 'pc:followers') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-myfans"></use></svg>我的粉丝
            </a>
        </li>
        <li>
            <a href="{{ route('pc:followings') }}" class="fs-16 @if ($route == 'pc:followings') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-attention"></use></svg>关注的人
            </a>
        </li>
        {{-- <li>
            <a href="{{ route('pc:rank')}}" class="fs-16 @if ($route == 'pc:rank') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-rank"></use></svg>排行榜
            </a>
        </li> --}}
        <li>
            <a href="{{ route('pc:collect') }}" class="fs-16 @if ($route == 'pc:collect') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collection"></use></svg>收藏的
            </a>
        </li>
        <li>
            <a href="{{ route('pc:account') }}" class="fs-16 @if ($route == 'pc:account') dy_59 @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-setting"></use></svg>设置
            </a>
        </li>

    </ul>
</div>
