@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#fff"@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="{{ $routes['resource'] }}/cropper/cropper.min.css">
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
    <div class="bas_right" id="J-input">
        <div class="bas_header">
            <img id="J-image-preview" src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png' }}" />
            <span class="con_cover ai_face_box">
                <div class="ai_upload">
                    
                    <input name="storage_task_id" id="task_id" type="hidden" value="" />
                </div>
                <span class="ai_btn" id="J-file-upload-btn">更换头像</span>
            </span>
        </div>
        <div class="f_div">
            <div class="f_tel bas_div">
                <label>昵称</label>
                <span class="f_span"><input type="text" name="name" value="{{$user['name'] }}" placeholder="输入昵称"></span>
            </div>
            <div class="f_tel bas_div">
                <label>简介</label>
                <span class="f_span"><input type="text" name="intro" value="{{$user['intro'] or ''}}" placeholder="输入简介"></span>
            </div>
            <div class="f_tel bas_div">
                <label>性别</label>
                  <span class="sex_item"><input name="sex" type="radio" value="1" class="s-ck" @if($user['sex'] == 1) checked="checked" @endif>男</span>
                  <span class="sex_item"><input name="sex" type="radio" value="2" class="s-ck" @if($user['sex'] == 2) checked="checked" @endif>女</span>
                  <span class="sex_item"><input name="sex" type="radio" value="3" class="s-ck" @if($user['sex'] == 3) checked="checked" @endif>不方便透露</span>
            </div>
            {{-- <div class="f_tel bas_div">
                <label>生日</label>
                <div class="f_select">
                    <span></span>
                    <select name="year" class="sel_year" rel="{{$user['year'] or 0}}"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="moth" class="sel_month" rel="{{$user['moth'] or 0}}"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="day" class="sel_day" rel="{{$user['day'] or 0}}"></select>
                </div>
            </div>
            <div class="f_tel bas_div">
                <label>公司</label>
                <span class="f_span"><input type="text" name="company" placeholder="输入公司名称" value="{{$user['company'] or ''}}"></span>
            </div> --}}
            <div class="f_tel bas_div" id="sel_area">
                <label>地区</label>
                <div class="f_select">
                    <span></span>
                    <select name="province" id="province" onchange="getArea(this);"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="city" id="city" onchange="getArea(this);"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="area" id="area"></select>
                </div>
            </div>
            <a href="javascript:;" class="f_sure" id="J-submit">保存</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/cropper/cropper.min.js"></script>
<script src="{{ $routes['resource'] }}/js/birthday.js"></script>
<script src="{{ $routes['resource'] }}/js/module.seting.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script> 
$('#J-image-preview, #J-file-upload-btn').on('click',function(){
    var html = '<div id="model">'
                    + '<div class="avatar-container" id="crop-avatar">'
                    + '<div class="avatar-upload">'
                    + '<input type="hidden" class="avatar-src" name="avatar_src">'
                    + '<label for="avatarInput">选择上传图片</label>'
                    + '<input type="file" class="avatar-input" id="avatarInput" name="avatar_file">'
                    + '</div>'
                    + '<div class="avatar-wrapper upload-box"></div>'
                    + '<div class="save-btn"><span></span><button type="button" class="btn btn-primary avatar-save">保存</button></div>'
                    + '</div></div>';
    ly.loadHtml(html, '上传头像', '600px', '500px;');
    $(function () {
        'use strict';
        var console = window.console || { log: function () {} };
        function CropAvatar($element) {
            this.$container = $element;
            this.$avatarInput = this.$container.find('.avatar-input');
            this.$avatarWrapper = this.$container.find('.avatar-wrapper');
            this.$avatarPreview = this.$container.find('.avatar-preview');
            this.$avatarSave = this.$container.find('.avatar-save');
            this.init();
        }
        // base64 
        function dataURLtoBlob(dataurl) {
            var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type:mime});
        }
        // get round avater
        function getRoundedCanvas(sourceCanvas) {
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');
            var width = sourceCanvas.width;
            var height = sourceCanvas.height;

            canvas.width = width;
            canvas.height = height;
            context.beginPath();
            context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
            context.strokeStyle = 'rgba(0,0,0,0)';
            context.stroke();
            context.clip();
            context.drawImage(sourceCanvas, 0, 0, width, height);

            return canvas;
        }
        CropAvatar.prototype = {
            constructor: CropAvatar,
            support: {
                fileList: !!$('<input type="file">').prop('files'),
                blobURLs: !!window.URL && URL.createObjectURL,
                formData: !!window.FormData
            },
            init: function () {
                this.support.datauri = this.support.fileList && this.support.blobURLs;
                this.fileUpload = {};
                this.addListener();
            },
            addListener: function () {
                this.$avatarInput.on('change', $.proxy(this.change, this));
                this.$avatarSave.on('click', $.proxy(this.click, this));
            },
            click: function () {
                if (this.fileUpload.mime_type) {
                    // 默认宽高 160
                    var croppedCanvas = this.$img.cropper('getCroppedCanvas', { width: 160, height: 90 });
                    var roundedCanvas = getRoundedCanvas(croppedCanvas); // 获取圆形头像
                    var blob = dataURLtoBlob(roundedCanvas.toDataURL());
                    var dataurl = roundedCanvas.toDataURL('image/png');
                    /*blob.name = this.fileUpload.origin_filename;
                    this.$avatarSave.text('上传中...');
                    fileUpload.init(blob, updateImg);*/
                    this.upload(blob, dataurl);                    
                } else {
                    ly.error('请选择上传文件', false);
                }
            },
            upload: function(file, url) {
                var _this = this;
                var formDatas = new FormData();
                    formDatas.append("avatar", file); 
                $.ajax({
                    url: '/api/v2/user/avatar',
                    type: 'POST',
                    data: formDatas,
                    contentType: false,
                    processData: false,                    
                    error: function(xml) {noticebox('修改失败请重试', 0);},
                    success: function(res) {
                        _this.insert(url);
                    }
                });                
            },
            insert: function(src) {
                $('#J-image-preview').attr('src', src);
                layer.closeAll();                
            },
            change: function () {
                var files,file;
                if (this.support.datauri) {
                    files = this.$avatarInput.prop('files');
                    if (files.length > 0) {
                        file = files[0];
                        this.fileUpload.mime_type = file.type;
                        this.fileUpload.origin_filename = file.name;
                        if (this.isImageFile(file)) {
                            if (this.url) {
                                URL.revokeObjectURL(this.url); // Revoke the old one
                            }
                            this.url = URL.createObjectURL(file);
                            this.startCropper();
                        }
                    }
                } else {
                    file = this.$avatarInput.val();
                    if (this.isImageFile(file)) {
                        this.syncUpload();
                    }
                }
            },
            startCropper: function () {
                var _this = this;
                if (this.active) {
                    this.$img.cropper('replace', this.url);
                } else {
                    this.$img = $('<img src="' + this.url + '">');
                    this.$avatarWrapper.empty().html(this.$img);
                    this.$img.cropper({
                        aspectRatio: 1, //设置剪裁容器的比例
                        viewMode: 1,
                        preview: this.$avatarPreview.selector, //添加额外的元素（容器）的预览
                    });
                    this.active = true;
                }
            },
            stopCropper: function () {
                if (this.active) {
                    this.$img.cropper('destroy');
                    this.$img.remove();
                    this.active = false;
                }
            },
            isImageFile: function (file) {
                if (file.type) {
                    return /^image\/\w+$/.test(file.type);
                } else {
                    return /\.(jpg|jpeg|png|gif)$/.test(file);
                }
            }
        };
        return new CropAvatar($('#crop-avatar'));
    });  
});
var username = "{{$user['name'] }}";
var arrSelect = ["{{$user['province'] or 0}}", "{{$user['city'] or 0}}", "{{$user['area'] or 0}}"];
$(function () {
    $.ms_DatePicker({
        YearSelector: ".sel_year",
        MonthSelector: ".sel_month",
        DaySelector: ".sel_day",
        FirstText:'请选择'
    });
    $.ms_DatePicker();
    //地区选择初始化
    init(1);
}); 
</script> 
@endsection
