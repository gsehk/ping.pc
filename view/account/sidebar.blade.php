@php $route = Route::currentRouteName(); @endphp
<div class="account_l">
    <ul class="account_menu">
        <a href="{{ Route('pc:account') }}">
            <li class="@if ($account_cur == 'index')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-ziliao"></use></svg>基本资料</li>
        </a>
        <a href="{{ Route('pc:tags') }}">
            <li class="@if ($account_cur == 'tags')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-biaoqian"></use></svg>标签管理</li>
        </a>
        <a href="{{ Route('pc:authenticate') }}">
            <li class="@if ($account_cur == 'authenticate')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-renzheng3-copy"></use></svg>认证管理</li>
        </a>
        <a href="{{ Route('pc:security')}}">
            <li class="@if ($account_cur == 'security')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xiugaimima"></use></svg>安全设置</li>
        </a>
        <a href="{{ Route('pc:wallet')}}">
            <li class="@if ($account_cur == 'wallet')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-qianbao"></use></svg>我的钱包</li>
        </a>
        <a href="{{ Route('pc:binds')}}">
            <li class="@if ($account_cur == 'binds')active @endif">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhanghaoguanli-"></use></svg>账号管理</li>
        </a>
    </ul>
</div>
