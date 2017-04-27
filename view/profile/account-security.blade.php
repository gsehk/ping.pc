@extends('layouts.default')

@section('content')
<div class="bas_cont">
    <div class="bas_left">
        <a href="{{Route('pc:account')}}">
            <div class="bas_list">
                <span @if($page == 'account') class="c_333" @endif>资料设置</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-auth'])}}">
            <div class="bas_list">
                <span @if($page == 'account-auth') class="c_333" @endif>认证</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-security'])}}">
            <div class="bas_list">
                <span @if($page == 'account-security') class="c_333" @endif>修改密码</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-bind'])}}">
            <div class="bas_list">
                <span @if($page == 'account-bind') class="c_333" @endif>绑定</span>
            </div>
        </a>
    </div>
    <div class="bas_right">
        <div class="f_div cer_div">
            <div class="f_tel bas_div">
                <label style=" margin-left: 15px;"><span class="cer_x">*</span>原密码</label>
                <span class="f_span"><input type="text" placeholder="输入原密码" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div" style="margin-left: -10px;">
                <label><span class="cer_x">*</span>设置新密码</label>
                <span class="f_span"><input type="text" placeholder="输入新密码" style="color:#ccc"></span>
            </div>

            <div class="f_tel bas_div" style="margin-left: -10px;">
                <label><span class="cer_x">*</span>确认新密码</label>
                <span class="f_span"><input type="text" placeholder="在此输入新密码" style="color:#ccc"></span>
            </div>
            <a href="#" class="f_sure">保存</a>
            <div class="cer_format"><span class="cer_x">*</span>附件格式：gif,jpg,jpeg,png;附件大小：不超过10M</div>
        </div>
    </div>
</div>
@endsection
