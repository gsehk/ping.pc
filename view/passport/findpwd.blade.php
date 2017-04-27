@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')

<div class=" forget_cont">
    <ul class="forget_ul">
        <li><a href="{{Route('pc:findPassword', ['type'=>1])}}" @if($type == 1) class="forget_333" @endif>手机找回</a></li>
        <li><a href="{{Route('pc:findPassword', ['type'=>2])}}" @if($type == 2) class="forget_333" @endif>邮箱注册</a></li>
    </ul>
    <div class="f_div">
        <div class="f_tel">
            <label>手机号</label>
            <span class="f_span"><input type="text" placeholder="输入11位手机号码" style="color:#ccc"/></span>
        </div>
        <div class="f_tel">
            <label>图形验证码</label>
            <span class="f_span"><input type="text" placeholder="输入图形验证码" style="color:#ccc" /></span>
            <div class="f_code"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/f_code.png') }}" /></div>
        </div>
        <div class="f_tel">
            <label>手机验证码</label>
            <span class="f_span"><input type="text" placeholder="输入手机验证码" style="color:#ccc" /></span>
        </div>
        <a href="#" class="f_sure">确认</a>
    </div>
</div>
@endsection
