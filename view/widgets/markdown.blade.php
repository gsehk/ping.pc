<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/markdown/pluseditor.css') }}">
<div id="layout" class="div">
    <div class="editormd">
        <textarea id="editor" style="display: none"></textarea>
    </div>
</div>
<script type="text/javascript" src="{{ asset('zhiyicx/plus-component-pc/markdown/pluseditor.min.js') }}"></script>
<style> .CodeMirror { height: {{$height or '435px'}} } </style>
<script type="text/javascript">
    var editor = new pluseditor({
        element: document.querySelector('#editor'),
        fileApiPath: TS.API+"/files/",
        placeholder: "{{ $place or '开始你的表演'}}",
        uploadFile: function(file, cb){
            fileUpload.init(file, function(image, f, id){
                cb(id);
            })
        }
    });
</script>