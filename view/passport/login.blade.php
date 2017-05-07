@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="lodin_cont">
    <div class="login_center">
        <div class="loginleft_bg">
            <div class="login_hy">欢迎来到ThinkSNS+</div>
            <div class="login_code">
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/login_code.png') }}" />
            </div>
            <div class="login_b">扫描二维码体验APP</div>
        </div>
        <div class="login_right">
            <form method="POST" id="login_form">
                <div class="l_tel">
                    <input type="text" placeholder="输入手机号" name="phone"/>
                </div>
                <div class="l_tel">
                    <input type="password" placeholder="输入密码" name="password"/>
                </div>
                <div class="l_mm">
                    <span class="l_tip" id="login_tip"></span>
                    <a href="{{Route('pc:findPassword')}}">
                        <span class="l_forget">忘记密码</span>
                    </a>
                </div>
                <a class="login_login" id="login_btn">登陆</a>
            </form>

            <div class="no_zh">
                <span class="no_span">没有账号？<a href="{{Route('pc:register')}}"><span>注册</span></a></span>
                <div class="del_share login_share" >
                    三方登陆：
                    <svg class="icon svdel_g1" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weibo"></use>
                    </svg>
                    <svg class="icon svdel_g2" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qq"></use>
                    </svg>
                    <svg class="icon svdel_g3" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-weixin"></use>
                    </svg>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.form.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/passport.js') }}"></script>
@endsection
