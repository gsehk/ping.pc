<link rel="stylesheet" type="text/css" href="{{ $url }}/wangEditor/wangEditor.min.css">
<style type="text/css">
    #editor {
        width: 100%;
        height: {{ $height }};
    }
</style>
<div id="editor">{!!$content or ''!!}</div>

<script src="{{ $url }}/wangEditor/wangEditor.min.js"></script>
<script type="text/javascript">
    $(function () {
        var editor = new wangEditor('editor');
        editor.config.menuFixed = false;
        editor.config.customUpload = true;  // 配置自定义上传的开关
        editor.config.customUploadInit = uploadInit;  // 配置上传事件，uploadInit方法已经在上面定义了
        editor.config.uploadImgFileName = 'fileUpload'
        editor.config.menus = [
            // 'source',
            '|',     // '|' 是菜单组的分割线
            'bold',
            'underline',
            'italic',
            'strikethrough',
            'eraser',
            'forecolor',
            'bgcolor',
            'img'
        ];
        function uploadInit(){
            // this 即 editor 对象
            var editor = this;
            // 编辑器中，触发选择图片的按钮的id
            var btnId = editor.customUploadBtnId;
            var i = document.createElement('input');
                i.type = 'file';
                i.name = 'fileUpload';
                i.style.display = "none";
            $('#'+btnId).on('click', function(){
                i.click();
                i.onchange = upload;
            });
        };
        var upload = function(e){
            var file = e.target.files[0];
            editor.showUploadProgress(50);
            fileUpload.init(file, insertHtml);
        };
        var insertHtml = function(image, f, storage_id){
            var url = request_url.images + storage_id;
            editor.command(null, 'insertHtml', '<img src="' + url + '" style="max-width:100%;"/>');
            editor.showUploadProgress(100);
            editor.hideUploadProgress();
        };
        editor.create();
    });
</script>