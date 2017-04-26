@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class="lodin_cont">
    <div class="login_center">
        <div class="loginleft_bg">
            <div class="login_hy">欢迎来到ThinkSNS+</div>
            <div class="login_code"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/login_code.png') }}" /></div>
            <div class="login_b">扫描二维码体验APP</div>
        </div>
        <div class="login_right">
            <div class="l_tel">
                <span>错误提示</span>
                <input type="text" placeholder="输入用户名/手机号" />
            </div>
            <div class="l_tel">
                <input type="text" placeholder="输入密码" />
            </div>
            <div class="l_mm">
                <!-- <input  type="checkbox" style=" float: left; margin-right: 5px;"/>忘记密码 -->
                <a href="{{Route('pc:findPassword')}}"><span class="l_forget">忘记密码</span></a>
            </div>
            <a href="#" class="login_login">登陆</a>

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
