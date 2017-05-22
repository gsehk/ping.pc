@extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="con_cont">
        <div class="con_left">
            <div class="con_title">
                <input type="hidden" id="news_id" name="id" value="{{$id or 0}}" />
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}" />
                <input type="text" id="subject-title" name="title" value="{{$title or ''}}" placeholder="请在此输入20字以内的标题"/>
            </div>
            <div class="con_title p_30">
                <input type="text" id="subject-abstract" name="abstract" value="{{$subject or ''}}" placeholder="请在此输入60字以内的文章摘要,不填写默认为文章内容前60字"/>
            </div>
            <!-- <div class="con_title">
                @if($cate)
                <select class="select" name="cate_id" id="subject-cate">
                    <option value="0">请选择</option>
                    @foreach($cate as $cat)
                        <option value="{{$cat['id']}}">{{$cat['name']}}</option>
                    @endforeach
                </select>
                @endif
            </div> -->
            <div class="con_place">
                @component('pcview::editor', 
                    [
                        'url'=>$routes['resource'],
                        'height'=>'530px', 
                        'content'=>$content ?? ''
                    ])
                @endcomponent
            </div>
            <div class="con_produce">
                <span class="con_bq" style="display: none;">
                    <img src="{{ $routes['resource'] }}/images/pro.png" /><input placeholder="添加标签，多个标签用逗号分开" />
                </span>
                <span class="con_cover ai_face_box">
                    <span id="J-show-tips">封面555*393px</span>
                    <a href="javascript:;" class="ai_face_btn">点击上传</a>
                    <div class="ai_upload">
                        <input id="J-file-upload"
                               type="file"
                               name="poster-input"
                               data-input="#task_id"
                               data-form="#release_form"
                               data-preview="#J-image-preview"
                               data-tips="#J-show-tips"
                               data-token="{{ csrf_token() }}"
                        >
                        <input name="task_id" id="task_id" type="hidden" value="{{$storage or 0}}" />
                    </div>
                </span>
            </div>
            <div class="con_word">
                <input type="text" id="subject-from" name="subject-from" value="{{$from or ''}}" placeholder="文章转载至何处（非转载可不填）" />
            </div>
            <div class="con_after">投稿后，我们将在两个工作日内给予反馈，谢谢合作！</div>
            <div class="con_btn">
                <button type="submit" class="subject-submit button con_a1" data-url="{{ route('pc:doSavePost',['type'=>2]) }}">存草稿</button>
                <button type="submit" class="subject-submit button con_a2" data-url="{{ route('pc:doSavePost',['type'=>1]) }}">投稿</button>
            </div>
        </div>
        <div class="con_right">
            <div class="conR_title">投稿须知</div>
            <div class="conR_artic">
                <p>  1、绝不搞事情</p>
                <p>  2、绝不搞事情</p>
                <p>  3、绝不搞事情</p>
            </div>
            <a href="{{route('pc:article',['type'=>2])}}">
            <div class="conR_bottom">
                我的草稿<span class="conR_num">{{ $count }}<i class="icon iconfont icon-icon07"></i></span>
            </div></a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.news.js"></script>
<script src="{{ $routes['resource'] }}/js/md5-min.js"></script>
<script type="text/javascript">
// $('select.select').select();

$('#J-file-upload').on('change', function(e){
    var file = e.target.files[0];
    fileUpload(file, updateImg);
});
var updateImg = function(image, f, task_id){
    $('#task_id').val(task_id);
    $('#J-show-tips').text(f.name);
}
</script>
@endsection
