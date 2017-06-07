<!DOCTYPE html>
<html>
<head>
    <title>头像上传</title>
    <link rel="stylesheet" type="text/css" href="{{ $routes['resource'] }}/cropper/cropper.min.css">
</head>
<style type="text/css">
.container{
}
.avatar-upload{
    margin:10px 0;
}
.avatar-wrapper {
    height: 364px;
    width: 100%;
    margin-top: 15px;
    box-shadow: inset 0 0 5px rgba(0,0,0,.25);
    background-color: #fcfcfc;
    overflow: hidden;
}
.avatar-preview {
    float: left;
    margin-top: 15px;
    margin-right: 15px;
    border: 1px solid #eee;
    border-radius: 4px;
    background-color: #fff;
    overflow: hidden;
}
.avatar-upload label {
    display: block;
    float: left;
    clear: left;
    width: 100px;
}
</style>
<body>
<div class="container" id="crop-avatar">
    <div class="avatar-upload">
      <input type="hidden" class="avatar-src" name="avatar_src">
      <label for="avatarInput">选择上传文件</label>
      <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
    </div>
    <div class="avatar-wrapper"></div>
    <button type="button" class="btn btn-primary btn-block avatar-save">Done</button>
</div>
<script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>
<script src="{{ $routes['resource'] }}/js/md5-min.js"></script>
<script src="{{ $routes['resource'] }}/cropper/cropper.min.js"></script>
<script type="text/javascript">
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
        var croppedCanvas = this.$img.cropper('getCroppedCanvas');
        var roundedCanvas = getRoundedCanvas(croppedCanvas); // 获取圆形头像
        var blob = dataURLtoBlob(roundedCanvas.toDataURL());
              blob.name = this.fileUpload.origin_filename;
              console.log(blob)
        // fileUpload(blob, test);
    },
    change: function () {
        var files,file;
        if (this.support.datauri) {
            files = this.$avatarInput.prop('files');
            if (files.length > 0) {
                file = files[0];
                this.fileUpload.mime_type = file.type;
                this.fileUpload.origin_filename = file.name;
                this.fileUpload.hash = '123456'+file.name;
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
  </script>
</body>
</html>