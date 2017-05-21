@extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="con_cont">
        <div class="con_left">
            <form id="release_form">
            <div class="con_title">
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}" />
                <input type="text" id="subject-title" name="title" placeholder="请在此输入20字以内的标题" data-easyform="length:6 20;" data-message="长度不合法" data-easytip="class:easy-red;"/>
            </div>
            <div class="con_title p_30">
                <input type="text" id="subject-abstract" name="abstract" placeholder="请在此输入60字以内的文章摘要,不填写默认为文章内容前60字" data-easyform="length:6 60;" data-message="长度不合法" data-easytip="class:easy-red;"/>
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
                @component('pcview::editor')
                    @slot('url') {{ $routes['resource'] }} @endslot
                    @slot('height') 530px @endslot
                @endcomponent
            </div>
            <div class="con_produce">
                <span class="con_bq" style="display: none">
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
                        <input name="task_id" id="task_id" type="hidden" value="" />
                    </div>
                </span>
            </div>
            <div class="con_word">
                <input type="text" id="subject-from" name="subject-from" placeholder="文章转载至何处（非转载可不填）" />
            </div>
            <div class="con_after">投稿后，我们将在两个工作日内给予反馈，谢谢合作！</div>
            <div class="con_btn">
                <button type="submit" class="subject-submit button con_a1" data-url="{{ route('pc:doSavePost',['type'=>2]) }}">存草稿</button>
                <button type="submit" class="subject-submit button con_a2" data-url="{{ route('pc:doSavePost',['type'=>1]) }}">投稿</button>
            </div>
        </div>
        </form>
        <div class="con_right">
            <div class="conR_title">投稿须知</div>
            <div class="conR_artic">
                <p>  1、韩媒集体摆乌龙:美否认三美否认三艘航母下周云集东北亚韩媒集体摆母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
                <p>  2、韩媒集体摆乌龙:美否认三艘航母下周云集东北亚韩媒集体摆乌龙美否认三艘航母下周云集东北亚韩媒集体摆乌龙美否认三艘航母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
                <p>  3、韩媒集体摆乌龙:美否认三艘航母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
            </div>
            <div class="conR_bottom">
                我的草稿<a href=""><span class="conR_num">{{ $count }}<i class="icon iconfont icon-icon07"></i></span></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.news.js"></script>
<script src="{{ $routes['resource'] }}/js/md5-min.js"></script>
<script src="{{ $routes['resource'] }}/js/easyform.js"></script>
<script type="text/javascript">
// $('select.select').select();

$(document).ready(function ()
{
    var v = $('#release_form').easyform();
    // v.is_submit = false;
    v.error = function (ef, i, r)
    {
        noticebox('用户名或密码错误', 0);
    };
    v.success = function (ef)
    {
        v.is_submit = false;
        release();
    };
});

$('#J-file-upload').on('change', function(e){
    var file = e.target.files[0];
    fileUpload(file, updateImg);
});
var updateImg = function(image, f, task_id){
    $('#task_id').val(task_id);
    $('#J-image-preview').attr('src', image.src);
}
</script>
@endsection
