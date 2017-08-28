@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/cropper/cropper.min.css')}}">
@endsection

@section('content')

<div class="account_container">
    <div class="account_wrap">

        {{-- 左侧导航 --}}
        @include('pcview::account.sidebar')

        <div class="account_r">
            <div class="account_c_c">
                <!-- 基本资料 -->
                <div class="account_tab" id="J-input">
                    <!-- label -->
                    <div class="perfext_title">
                        <p>基本资料</p>
                    </div>
                    <!-- /label -->
                    <!-- 更改头像 -->
                    <div class="perfect_row mb30">
                        <div class="account_heder">
                            <div class="header">
                                <img id="J-image-preview" src="{{ $user['avatar'] or URL::asset('zhiyicx/plus-component-pc/images/avatar.png')}}">
                                <input id="task_id" name="storage_task_id" type="hidden"/>
                            </div>
                            <a class="perfect_btn" id="J-file-upload-btn" href="javascript:;">更改头像</a>
                        </div>
                    </div>
                    <!-- /更改头像 -->
                    <!-- txt -->
                    <div class="perfect_row mb30">
                        <form action="#">
                            <div class="account_form_row">
                                <label for="name">昵称</label>
                                <input id="name" name="name" type="text" value="{{$user['name'] }}" />
                            </div>
                            <div class="account_form_row">
                                <label for="bio">简介</label>
                                <input id="bio" name="bio" type="text" value="{{$user['bio'] or ''}}" />
                            </div>
                            <div class="account_form_row">
                                <label>性别</label>
                                <div class="input">
                                <span>
                                    <input @if($user['sex'] == 1) checked="checked" @endif id="male" name="sex" type="radio" value="1" />
                                    <label for="male">男</label>
                                </span>
                                <span>
                                    <input @if($user['sex'] == 2) checked="checked" @endif id="female" name="sex" type="radio" value="2" />
                                    <label for="female">女</label>
                                </span>
                                <span>
                                    <input @if($user['sex'] == 0) checked="checked" @endif id="secret" name="sex" type="radio" value="0" />
                                    <label for="secret">不方便透露</label>
                                </span>
                                </div>
                            </div>
                            {{-- <div class="account_form_row">
                                <label>生日</label>
                                <div class="input">
                                    <select name="year" id="year">
                                        <option value="2017">2017</option>
                                    </select>
                                    <select name="month" id="month">
                                        <option value="02">2月</option>
                                    </select>
                                    <select name="day" id="day">
                                        <option value="26">26日</option>
                                    </select>
                                </div>
                            </div>
                            <div class="account_form_row">
                                <label for="company">公司</label>
                                <input name="company" id="company" type="text">
                            </div> --}}
                            <div class="account_form_row">
                                <label for="area">地区</label>
                                <select id="province" name="province" onchange="getArea(this);"></select>
                                <select id="city" name="city" onchange="getArea(this);"></select>
                                <select id="area" name="area"></select>
                            </div>
                        </form>
                    </div>
                    <!-- /txt -->
                    <div class="perfect_btns">
                        <a href="javascript:;" class="perfect_btn save" id="J-user-info">保存</a>
                    </div>
                </div>
                <!-- /基本资料 -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('zhiyicx/plus-component-pc/cropper/cropper.min.js')}}"></script>
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.account.js')}}"></script>
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
<script>
var username = "{{$user['name'] }}";
var arrSelect = ["{{$user['city'][0] or ''}}", "{{$user['city'][1] or ''}}", "{{$user['city'][2] or ''}}"];
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
//地区选择初始化
init(1);
</script>
@endsection