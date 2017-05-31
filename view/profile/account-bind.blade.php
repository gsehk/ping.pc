@extends('pcview::layouts.default')
@section('bgcolor')style="background-color:#fff"@endsection
@section('content')
<div class="bas_cont">
    <div class="bas_left">
        <a href="{{ route('pc:account') }}">
            <div class="bas_list">
                <span @if($page == 'account') class="c_333" @endif>资料设置</span>
            </div>
        </a>
        <a href="{{ route('pc:account', ['page'=>'account-auth']) }}">
            <div class="bas_list">
                <span @if($page == 'account-auth') class="c_333" @endif>认证</span>
            </div>
        </a>
        <a href="{{ route('pc:account', ['page'=>'account-security']) }}">
            <div class="bas_list">
                <span @if($page == 'account-security') class="c_333" @endif>修改密码</span>
            </div>
        </a>
        <!-- <a href="{{ route('pc:account', ['page'=>'account-bind']) }}">
            <div class="bas_list">
                <span @if($page == 'account-bind') class="c_333" @endif>绑定</span>
            </div>
        </a> -->
    </div>
    <div class="bas_right">
        <div class="f_div cer_div">
            <div class="f_tel bin_div">
                <span class="bin_name">绑定QQ</span>
                <span class="bin_line"></span>
                <a href="#" class="bai_go">去绑定</a>
            </div>
            <div class="f_tel bin_div">
                <span class="bin_name">绑定微信</span>
                <span class="bin_line"></span>
                <a href="#" class="bai_go bg_ccc">已绑定</a>
            </div>
            <div class="f_tel bin_div">
                <span class="bin_name">绑定微博</span>
                <span class="bin_line"></span>
                <a href="#" class="bai_go">去绑定</a>
            </div>
        </div>
    </div>
</div>
@endsection
