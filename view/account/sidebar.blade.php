@php $route = Route::currentRouteName(); @endphp
<div class="account_l">
    <ul class="account_menu">
        <a href="{{ Route('pc:account') }}">
            <li class="@if (Request::path() == 'account/index')active @endif"><i class="iconfont icon-ziliao"></i>基本资料</li>
        </a>
        <a href="{{ Route('pc:tags') }}">
            <li class="@if (Request::path() == 'account/tags')active @endif"><i class="iconfont icon-ziliao"></i>标签管理</li>
        </a>
        <a href="{{ Route('pc:authenticate') }}">
            <li class="@if (Request::path() == 'account/authenticate')active @endif"><i class="iconfont icon-ziliao"></i>认证管理</li>
        </a>
        <a href="{{ Route('pc:security')}}">
            <li class="@if (Request::path() == 'account/security')active @endif"><i class="iconfont icon-ziliao"></i>安全设置</li>
        </a>
        <a href="{{ Route('pc:wallet')}}">
            <li class="@if (Request::path() == 'account/wallet')active @endif"><i class="iconfont icon-ziliao"></i>我的钱包</li>
        </a>
    </ul>
</div>
