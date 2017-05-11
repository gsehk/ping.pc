@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="forget_cont" style="height:640px;">
    <ul class="forget_ul">

        <li><a href="{{Route('pc:register', ['type'=>1])}}" class="forget_333">手机注册</a></li>
    </ul>
    
    <div class="f_div">
        <form method="POST" id="reg_form">
            <div class="f_tel">
                <label>手机号</label>
                <span class="f_span" id="phone"><input type="text" placeholder="输入11位手机号码" name="phone" maxlength="11"/></span>
                <label class="error" id="phone_tip"></label>
            </div>
            <div class="f_tel">
                <label>图形验证码</label>
                <span class="f_span w_280"><input type="text" placeholder="输入图形验证码" name="captchacode" maxlength="6"/></span>
                <img onclick="re_captcha()" src="{{ Route('pc:captcha', ['tmp'=>1]) }}"  alt="验证码" title="刷新图片" id="captchacode" class="f_captcha">
                <label class="error" id="captcha_tip"></label>
            </div>
            <div class="f_tel ">
                <label>手机验证码</label>
                <span class="f_span w_280"><input type="text" placeholder="输入手机验证码" name="code" maxlength="4"/></span>
                <span class="get_code" id="smscode">获取短信验证码</span>
                <label class="error" id="smscode_tip"></label>
            </div>
            <div class="f_tel">
                <label>设置昵称</label>
                <span class="f_span" id="name"><input type="text" placeholder="2-10个字符" name="name"/></span>
                <label class="error" id="name_tip"></label>
            </div>
            <div class="f_tel">
                <label>设置密码</label>
                <span class="f_span" id="password"><input type="password" placeholder="限6-15个字符，区分大小写" name="password"/></span>
                <label class="error" id="password_tip"></label>
            </div>
            <div class="f_tel">
                <label>确认密码</label>
                <span class="f_span" id="repassword"><input type="password" placeholder="再次输入密码" name="repassword"/></span>
                <label class="error" id="repassword_tip"></label>
            </div>

            <input type="hidden" name="device"/ >
            <a id="reg_btn" class="f_sure">注册</a>
        </form>

        <!-- 邮箱注册 -->
        <!-- <div class="f_tel">
            <label>常用邮箱</label>
            <span class="f_span"><input type="text" placeholder="输入常用邮箱" /></span>
        </div>
        <div class="f_tel">
            <label>图形验证码</label>
            <span class="f_span"><input type="text" placeholder="输入图形验证码" /></span>
            <div class="f_code"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/f_code.png') }}" /></div>
        </div>
        <div class="f_tel">
            <label>设置昵称</label>
            <span class="f_span"><input type="text" placeholder="2-10个字符" /></span>
        </div>
        <div class="f_tel">
            <label>设置密码</label>
            <span class="f_span"><input type="text" placeholder="限6-15个字符，区分大小写" /></span>
        </div>
        <div class="f_tel">
            <label>确认密码</label>
            <span class="f_span"><input type="text" placeholder="再次输入密码" /></span>
        </div>
        <a href="#" class="f_sure">注册</a> -->
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.form.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/passport.js') }}"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
<script>
$().ready(function() {
});
</script>
@endsection
