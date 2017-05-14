@extends('layouts.default')

@section('content')

<div class="success_div"></div>
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
        <form id="auth_form">
            <div class="f_tel bas_div">
                <label><span class="cer_x">*</span>真实姓名</label>
                <span class="f_span"><input name="realname" type="text" id="realname" data-easyform="char-chinese;length:4 16;" data-message="姓名必须为4—16位" data-easytip="class:easy-blue;"></span>
            </div>
            <div class="f_tel bas_div" style="margin-left: -15px;">
                <label><span class="cer_x">*</span>身份证号码</label>
                <span class="f_span"><input name="idcard" type="text" id="idcard" data-easyform="regex:(^\d{15}$)|(^\d{17}([0-9]|X)$);" data-message="证件号码错误" data-easytip="class:easy-blue;"></span>
            </div>

            <div class="f_tel bas_div">
                <label><span class="cer_x">*</span>联系方式</label>
                <span class="f_span"><input name="phone" type="text" id="phone" data-easyform="mobile;" data-message="手机号码错误" data-easytip="class:easy-blue;"></span>
            </div>
            <div class="f_tel bas_div">
                <label style="margin-right: 6px !important;">认证补充</label>
                <span class="f_span"><input name="info" type="text" data-easyform="null;"></span>
            </div>
            <div class="f_tel bas_div">
                <label style="margin-right: 6px !important;">认证资料</label>
                <span class="f_span" style="border-bottom: none;">
                    <input id="J-file-upload"
                           type="file"
                           name="poster-input"
                           data-input="#task_id"
                           data-form="#auth_form"
                           data-preview="#J-image-preview"
                           data-token="{{ csrf_token() }}"
                    >
                    <input name="task_id" id="task_id" type="hidden" value="" />
                </span>
                <!-- <div class='loading'><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/loading.png') }}" class='load'>上传中</div> -->
                <!-- <div class="cer_attachment">附件格式：gif,jpg,jpeg,png;附件大小：不超过10M</div> -->
            </div>
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token" />
            <button type="submit" id="J-user-verif" class="f_sure" data-url="{{route('pc:doSaveAuth')}}">提交</button>
            <div class="cer_format"><span class="cer_x">*</span>附件格式：gif, jpg, jpeg, png;附件大小：不超过10M</div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/easyform.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/module.seting.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/md5-min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function ()
{
    var v = $('#auth_form').easyform();
    v.error = function (ef, i, r)
    {
        console.log("Error事件：" + i.id + "对象的值不符合" + r + "规则");
    };
    v.success = function (ef)
    {
        v.is_submit = false;
        userVerif();
    };
});
var file = $('#J-file-upload').files[0];
$('#J-file-upload').on('change', {file: file, callback:ajaxFileUpload},getImgInfo);
</script>
@endsection
