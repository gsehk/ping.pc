<link rel="stylesheet" type="text/css" href="{{ $url }}/markdown/css/editormd.css">
<div id="layout" class="div">
    <div class="editormd" id="test-editormd">
        <textarea></textarea>
    </div>
</div>
<!--<script type="text/javascript" charset="utf-8" src="__THEME__/js/markdown/js/zepto.min.js"></script>-->
<script type="text/javascript" charset="utf-8" src="{{ $url }}/markdown/js/editormd.js"></script>

<script type="text/javascript">
    var editor = editormd("test-editormd",{
        id   : "editormd",
        width: "{{$width}}",
        height: "{{$height}}",
        watch : false,
        path : "{{ $url }}/markdown/lib/",
        saveHTMLToTextarea : true,
        imageUpload : true,
        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
        imageUploadURL : "/api/v2/files",
        uploadFuc: function (callback) {
            var file  =  document.getElementById("editormd-image-file").files[0];
            fileUpload.init(file, insertHtml);

            return false;
        }
    });

    function insertHtml (image, f, storage_id) {

        "{{ $routes['storage']}}" + storage_id;

        if (storage_id > 0)
        {

        }
        else
        {
            alert('是啊比');
        }
    }
    editor.setToolbarAutoFixed(false);
</script>