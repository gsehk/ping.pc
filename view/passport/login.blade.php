@section('title')
登录
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="login_container">
    <div class="login_left">
        <img src="@if(isset($config['common']['login_bg'])) {{ $routes['storage'] . $config['common']['login_bg'] }} @else {{ $routes['resource'] }}/images/login_bg.png @endif"/>
    </div>
    <div class="login_right">
        <form method="POST" id="login_form">
            <div class="login_input">
                <input type="text" placeholder="输入手机号/邮箱/昵称" name="login"/>
            </div>
            <div class="login_input">
                <input type="password" placeholder="输入密码" name="password"/>
            </div>
            <div class="login_extra">
                <a class="forget_pwd" href="{{ route('pc:findpassword') }}">忘记密码</a>
            </div>
            <a class="login_button" id="login_btn">登录</a>
        </form>

        <div class="login_right_bottom">
            <span class="no_account">没有账号？<a href="{{ route('pc:register') }}"><span>注册</span></a></span>
            <div class="login_share" >
                三方登录：
                <a href="{{route('pc:socialite', 'weibo')}}">
                    <svg class="icon icon_weibo" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use>
                    </svg>
                </a>
                <a href="{{route('pc:socialite', 'qq')}}">
                    <svg class="icon icon_qq" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qq"></use>
                    </svg>
                </a>
                <a href="{{route('pc:socialite', 'wechat')}}">
                    <svg class="icon icon_weixin" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weixin"></use>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.form.js') }} "></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.passport.js') }} "></script>
<script type="text/javascript">
$(function(){ 
    $(document).keydown(function(event){ 
        if(event.keyCode==13){ 
            $("#login_btn").click(); 
        }
    });
}); 
</script>
@endsection
