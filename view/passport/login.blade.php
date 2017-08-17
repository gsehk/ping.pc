@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="login_container">
    <div class="login_left">
        <div class="login_hy">欢迎来到ThinkSNS+</div>
        <div class="login_code">
            <!-- <img src="{{ $routes['resource'] }}/images/login_code.png" /> -->
            <img src="{{ $routes['resource'] }}/images/login_code.png" />
        </div>
        <div class="login_scan">扫描二维码体验APP</div>
    </div>
    <div class="login_right">
        <form method="POST" id="login_form">
            <div class="login-input">
                <input type="text" placeholder="输入手机号" name="login"/>
            </div>
            <div class="login-input">
                <input type="password" placeholder="输入密码" name="password"/>
            </div>
            <div class="forget_pwd">
                <a href="{{ route('pc:findpassword') }}">
                    <span class="l_forget">忘记密码</span>
                </a>
            </div>
            <a class="login_button" id="login_btn">登录</a>
        </form>

        <div class="login_right_bottom">
            <span class="no_account">没有账号？<a href="{{ route('pc:register') }}"><span>注册</span></a></span>
            <div class="login_share" >
                三方登录：
                <a href="javascript:noticebox('暂未开放', 0)">
                <svg class="icon svdel_g1" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use>
                </svg>
                </a>
                <a href="javascript:noticebox('暂未开放', 0)">
                <svg class="icon svdel_g2" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qq"></use>
                </svg>
                </a>
                <a href="javascript:noticebox('暂未开放', 0)">
                <svg class="icon svdel_g3" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weixin"></use>
                </svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/jquery.form.js"></script>
<script src="{{ $routes['resource'] }}/js/module.passport.js"></script>
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
