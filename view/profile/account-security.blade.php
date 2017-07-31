@extends('pcview::layouts.default')
@section('bgcolor')style="background-color:#fff"@endsection
@section('content')

<div class="success_div"></div>
<div class="bas_cont">
    <div class="bas_left">
        <a href="{{ route('pc:account') }}">
            <div class="bas_list">
                <span @if($page == 'account') class="c_333" @endif>资料设置</span>
            </div>
        </a>
        {{-- <a href="{{ route('pc:account', ['page'=>'account-auth']) }}">
            <div class="bas_list">
                <span @if($page == 'account-auth') class="c_333" @endif>认证</span>
            </div>
        </a> --}}
        <a href="{{ route('pc:account', ['page'=>'account-security']) }}">
            <div class="bas_list">
                <span @if($page == 'account-security') class="c_333" @endif>修改密码</span>
            </div>
        </a>
        {{-- <a href="{{ route('pc:account', ['page'=>'account-bind']) }}">
            <div class="bas_list">
                <span @if($page == 'account-bind') class="c_333" @endif>绑定</span>
            </div>
        </a> --}}
    </div>
    <div class="bas_right">
        <div class="">
        <form id="resetpwd_form">
            <div class="f_tel bas_div">
                <label style=" margin-left: 18px;"><span class="cer_x">*</span>原密码</label>
                <span class="f_span"><input name="old_password" type="password" id="old_password" placeholder="输入原密码"></span>
            </div>
            <div class="f_tel bas_div" style="margin-left: -10px;">
                <label><span class="cer_x">*</span>设置新密码</label>
                <span class="f_span">
                    <input name="password" type="password" id="password" placeholder="输入新密码">
                </span>
            </div>

            <div class="f_tel bas_div" style="margin-left: -10px;">
                <label><span class="cer_x">*</span>确认新密码</label>
                <span class="f_span">
                    <input name="password_confirmation"  type="password" id="password_confirmation" placeholder="确认原密码">
                </span>
            </div>
            <button type="submit" id="J-reset-pwd" class="f_sure">保存</button>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/easyform.js"></script>
<script src="{{ $routes['resource'] }}/js/module.seting.js"></script>
<script type="text/javascript">
$(document).ready(function ()
{
    var v = $('#resetpwd_form').easyform();
    // v.is_submit = false;
    v.error = function (ef, i, r)
    {
        console.log("Error事件：" + i.id + "对象的值不符合" + r + "规则");
    };
    v.success = function (ef)
    {
        v.is_submit = false;
        resetPwd();
    };
});
</script>
@endsection
