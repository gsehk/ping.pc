<link rel="stylesheet" type="text/css" href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/wangEditor.min.css') }}">
<style type="text/css">
    #editor {
        width: 100%;
        height: {{$height}};
    }
</style>
<div id="editor"><p>请输入内容...</p></div>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/wangEditor.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        var editor = new wangEditor('editor');
        // editor.config.hideLinkImg = true;
        editor.config.uploadImgUrl = '/';
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
        editor.create();
    });
</script>
