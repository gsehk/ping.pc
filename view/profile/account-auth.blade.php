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
                <label><span class="cer_x">*</span>真实姓名</label>
                <span class="f_span"><input type="text" placeholder="输入真实姓名" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div" style="margin-left: -15px;">
                <label><span class="cer_x">*</span>身份证号码</label>
                <span class="f_span"><input type="text" placeholder="输入身份证号" style="color:#ccc"></span>
            </div>

            <div class="f_tel bas_div">
                <label><span class="cer_x">*</span>联系方式</label>
                <span class="f_span"><input type="text" placeholder="输入联系方式" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div">
                <label style="margin-right: 6px !important;">认证补充</label>
                <span class="f_span"><input type="text" placeholder="" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div">
                <label style="margin-right: 6px !important;">认证资料</label>
                <span class="f_span" style="border-bottom: none;"><input type="file"style="color:#ccc;" ></span>
                <div class="cer_attachment">附件格式：gif,jpg,jpeg,png;附件大小：不超过10M</div>
            </div>
            <a href="#" class="f_sure">提交</a>
            <div class="cer_format"><span class="cer_x">*</span>附件格式：gif,jpg,jpeg,png;附件大小：不超过10M</div>
        </div>
    </div>
</div>
@endsection
