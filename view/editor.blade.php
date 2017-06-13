<link rel="stylesheet" type="text/css" href="{{ $url }}/wangEditor/wangEditor.min.css">
<style type="text/css">
    #editor {
        width: 100%;
        height: {{$height}};
    }
</style>
<div id="editor">{!!$content or ''!!}</div>
<script src="{{ $url }}/wangEditor/wangEditor.min.js"></script>
<script type="text/javascript">
    $(function () {
        var editor = new wangEditor('editor');
        // editor.config.hideLinkImg = true;
        editor.config.menuFixed = false;
        editor.config.uploadImgServer = '/upload';
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

        editor.config.uploadImgParams = {
            token: 'abcdef12345',   // 属性值会自动进行 encode ，此处无需 encode
            passwd: '123'
        }
        editor.config.uploadImgHooks = {
            before: function (xhr, editor, files) {
                // 图片上传之前触发
                // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件
            },
            success: function (xhr, editor, result) {
                // 图片上传并返回结果，图片插入成功之后触发
                // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
            },
            fail: function (xhr, editor, result) {
                alert();
                // 图片上传并返回结果，但图片插入错误时触发
                // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
            },
            error: function (xhr, editor) {
                alert();
                // 图片上传出错时触发
                // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
            },
            timeout: function (xhr, editor) {
                // 图片上传超时时触发
                // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
            },

            // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
            customInsert: function (insertImg, result, editor) {
                // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

                // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                var url = result.url
                insertImg(url)
            }
        }


        editor.create();
    });
</script>
