@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="forget_cont" style="height:640px;">
    <ul class="forget_ul">
        <li><a href="{{ route('pc:register', ['type'=>1]) }}" class="forget_333">手机注册</a></li>
    </ul>
    
    <div class="f_div">
        <form method="POST" id="reg_form">
            <div class="f_tel">
                <label>手机号</label>
                <span class="f_span" id="phone"><input type="text" placeholder="输入11位手机号码" name="phone" maxlength="11" id="inputphone"/></span>
                <label class="error" id="phone_tip"></label>
            </div>
            <div class="f_tel">
                <label>图形验证码</label>
                <span class="f_span w_280"><input type="text" placeholder="输入图形验证码" name="captchacode" maxlength="6" autocomplete="off"/></span>
                <img onclick="re_captcha()" src="{{ route('pc:captcha', ['tmp'=>1]) }}"  alt="验证码" title="刷新图片" id="captchacode" class="f_captcha">
                <label class="error" id="captcha_tip"></label>
            </div>
            <div class="f_tel ">
                <label>手机验证码</label>
                <span class="f_span w_280"><input type="text" placeholder="输入手机验证码" name="code" maxlength="6" autocomplete="off"/></span>
                <span class="get_code" id="smscode" type="register">获取短信验证码</span>
                <label class="error" id="smscode_tip"></label>
            </div>
            <div class="f_tel">
                <label>设置昵称</label>
                <span class="f_span" id="name"><input type="text" placeholder="2-10个字符" name="name" autocomplete="off"/></span>
                <label class="error" id="name_tip"></label>
            </div>
            <div class="f_tel">
                <label>设置密码</label>
                <span class="f_span" id="password"><input type="password" placeholder="限6-15个字符，区分大小写" name="password" autocomplete="off"/></span>
                <label class="error" id="password_tip"></label>
            </div>
            <div class="f_tel">
                <label>确认密码</label>
                <span class="f_span" id="repassword"><input type="password" placeholder="再次输入密码" name="repassword" autocomplete="off"/></span>
                <label class="error" id="repassword_tip"></label>
            </div>

            <a id="reg_btn" class="f_sure">注册</a>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/jquery.form.js"></script>
<script src="{{ $routes['resource'] }}/js/module.passport.js"></script>
@endsection
