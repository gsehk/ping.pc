@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class=" forget_cont" style="height:640px;">
    <ul class="forget_ul">

        <li><a href="{{Route('pc:register', ['type'=>1])}}" @if ($type == 1) class="forget_333" @endif>手机注册</a></li>
        <li><a href="{{Route('pc:register', ['type'=>2])}}" @if ($type == 2) class="forget_333" @endif>邮箱注册</a></li>
    </ul>
    
    <div class="f_div">
    @if ($type == 1)
        <div class="f_tel">
            <label>手机号</label>
            <span class="f_span"><input type="text" placeholder="输入11位手机号码" style="color:#ccc"/></span>
        </div>
        <div class="f_tel">
            <label>图形验证码</label>
            <span class="f_span"><input type="text" placeholder="输入图形验证码" style="color:#ccc" /></span>
            <div class="f_code"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/f_code.png') }}" /></div>
        </div>
        <div class="f_tel ">
            <label>手机验证码</label>
            <span class="f_span w_370"><input type="text" placeholder="输入手机验证码" style="color:#ccc" /></span>
            <span class="get_code">获取短信验证码</span>
        </div>
        <div class="f_tel">
            <label>设置昵称</label>
            <span class="f_span"><input type="text" placeholder="2-10个字符" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>设置密码</label>
            <span class="f_span"><input type="text" placeholder="限6-15个字符，区分大小写" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>确认密码</label>
            <span class="f_span"><input type="text" placeholder="再次输入密码" style="color:#ccc" /></span>
        </div>
        <a href="#" class="f_sure">注册</a>
    @elseif ($type == 2)
        <div class="f_tel">
            <label>常用邮箱</label>
            <span class="f_span"><input type="text" placeholder="输入常用邮箱" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>图形验证码</label>
            <span class="f_span"><input type="text" placeholder="输入图形验证码" style="color:#ccc" /></span>
            <div class="f_code"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/f_code.png') }}" /></div>
        </div>
        <div class="f_tel">
            <label>设置昵称</label>
            <span class="f_span"><input type="text" placeholder="2-10个字符" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>设置密码</label>
            <span class="f_span"><input type="text" placeholder="限6-15个字符，区分大小写" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>确认密码</label>
            <span class="f_span"><input type="text" placeholder="再次输入密码" style="color:#ccc" /></span>
        </div>
        <a href="#" class="f_sure">注册</a>
        @endif
    </div>

</div>
@endsection
