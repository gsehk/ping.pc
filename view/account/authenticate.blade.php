@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">
<div class="account_wrap">

{{-- 左侧导航 --}}
@include('pcview::account.sidebar')

<div class="account_r">
<div class="account_c_c">
<div class="account_tab">
    <div class="perfext_title">
        <select class="J-authenticate-type type" name="type" >
            <option value="user">个人认证</option>
            <option value="org">机构认证</option>
        </select>
    </div>
    {{-- 个人认证 --}}
    <div class="user_authenticate" id="J-input-user">
        <div class="account_form_row">
            <label class="w80 required" for="realName"><font color="red">*</font>真实姓名</label>
            <input id="realName" name="name" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="IDNumber"><font color="red">*</font>身份证号码</label>
            <input  id="IDNumber" name="number" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="contact"><font color="red">*</font>联系方式</label>
            <input id="contact" name="phone" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc"><font color="red">*</font>认证描述</label>
            <div class="text_box desc" contenteditable="true"></div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc"><font color="red">*</font>认证资料</label>
            <div class="upload_file">
                <span class="file_box">
                    <img id="J-image-preview-front" src="{{ URL::asset('zhiyicx/plus-component-pc/images/pic_upload.png')}}" />
                    <input class="J-file-upload front" type="file" name="file-front" />
                </span>
                <span  class="file_box">
                    <img id="J-image-preview-behind" src="{{ URL::asset('zhiyicx/plus-component-pc/images/pic_upload.png')}}" />
                    <input class="J-file-upload behind" type="file" name="file-behind" />
                </span>
            </div>
            <input name="front_id" id="front_id" type="hidden" />
            <input name="behind_id" id="behind_id" type="hidden" />
        </div>
        <div class="account_form_row">
            <div class="cer_format">附件格式：gif, jpg, jpeg, png;附件大小：不超过10M</div>
        </div>
        <div class="perfect_btns">
            <a class="perfect_btn save J-authenticate-btn" href="javascript:;">保存</a>
        </div>
    </div>
    {{-- /个人认证 --}}

    {{-- 机构认证 --}}
    <div class="org_authenticate" id="J-input-org" style="display: none;">
        <div class="account_form_row">
            <label class="w80 required" for="orgName"><font color="red">*</font>机构名称</label>
            <input id="orgName" name="org_name" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="orgAddress"><font color="red">*</font>机构地址</label>
            <input  id="orgAddress" name="org_address" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="ruler"><font color="red">*</font>负责人</label>
            <input  id="ruler" name="name" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="rulerPhone"><font color="red">*</font>负责人电话</label>
            <input id="rulerPhone" name="phone" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="license"><font color="red">*</font>营业执照号</label>
            <input  id="license" name="number" type="text">
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc"><font color="red">*</font>认证描述</label>
            <div class="text_box desc" contenteditable="true"></div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc"><font color="red">*</font>认证资料</label>
            <div class="upload_file">
                <span class="file_box">
                    <img id="J-image-preview" src="{{ URL::asset('zhiyicx/plus-component-pc/images/pic_upload.png')}}" />
                    <input class="J-file-upload org" type="file" name="file-front" />
                </span>
            </div>
            <input name="license_id" id="license_id" type="hidden" />
        </div>
        <!-- <div class="account_form_row">
            <div class="cer_format">附件格式：gif, jpg, jpeg, png;附件大小：不超过10M</div>
        </div> -->
        <div class="perfect_btns">
            <a class="perfect_btn save J-authenticate-btn" href="javascript:;">保存</a>
        </div>
    </div>
    {{-- /机构认证 --}}
</div>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.account.js')}}"></script>
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
<script>
var authType = 'user';
$('.J-authenticate-type').on('change', function(e){
    if ($(this).val() == 'user') {
        $('.org_authenticate').hide();
        $('.user_authenticate').show();
        authType = 'user';
    }
    if ($(this).val() == 'org') {
        $('.user_authenticate').hide();
        $('.org_authenticate').show();
        authType = 'org';
    }
});
/*  提交用户认证信息*/
$('.J-authenticate-btn').on('click', function(e) {
    var getArgs = function() {
        var inp = $('#J-input-'+authType+' input, #J-input-'+authType+' select').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());
        };
        args.set('desc', $('.text_box').text());
        args.set('type', $('.J-authenticate-type').val());

        return args.get();
    };

    if (authType == 'user') {
        getArgs().files = [getArgs().front_id, getArgs().behind_id];
    }

    if (authType == 'org') {
        getArgs().files = [getArgs().license_id];
    }
    $.ajax({
        url: '/api/v2/user/certification',
        type: 'POST',
        data: getArgs(),
        dataType: 'json',
        success: function(res, data, xml) {
            if (xml.status ===  201) {
                noticebox(res.message, 1, 'refresh');
            }
        },
        error: function(xhr){
            showError(xhr.responseJSON);
        }
    });
});

$('.J-file-upload').on('change', function(e){
    var file = e.target.files[0];
    if ($(this).hasClass('org')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#license_id').val(file_id);
            $('#J-image-preview').attr('src', image.src);
        });
    }
    if ($(this).hasClass('front')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#front_id').val(file_id);
            $('#J-image-preview-front').attr('src', image.src);
            });
    }
    if ($(this).hasClass('behind')) {
        fileUpload.init(file, function(image, f, file_id){
            $('#behind_id').val(file_id);
            $('#J-image-preview-behind').attr('src', image.src);
        });
    }
});
</script>
@endsection