@extends('layouts.default')

@section('content')
<div class="dy_bg">
    <div class="con_cont">
        <div class="con_left">
            <div class="con_title">
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}" />
                <input type="text" id="subject-title" name="title" placeholder="请在此输入20字以内的标题" />
            </div>
            <div class="con_title p_30">
                <input type="text" id="subject-abstract" name="abstract" placeholder="请在此输入60字以内的文章摘要,不填写默认为文章内容前60字" />
            </div>
            <div class="con_place">
                <textarea id="subject-content" placeholder="代替内容"></textarea>
            </div>
            <div class="con_produce">
                <span class="con_bq">
                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/pro.png') }}" /><input placeholder="添加标签，多个标签用逗号分开" />
                </span>
                <span class="con_cover ai_face_box">
                    封面555*393px
                    <a href="javascript:;" class="ai_face_btn">点击上传</a>
                    <div class="ai_upload">
                        <input id="J-file-upload"
                               type="file"
                               name="poster-input"
                               data-input="#form_logo"
                               data-preview="#J-image-preview"
                               data-js="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/ajaxfileupload.js') }}"
                               data-url="{{route('pc:uploadImg')}}"
                               data-token="{{ csrf_token() }}"
                        >
                        <input name="logo" id="form_logo" type="hidden" value="{$logo}" />
                    </div>
                </span>
            </div>
            <div class="con_word">
                <input type="text" id="subject-from" name="subject-from" placeholder="文章转载至何处（非转载可不填）" />
            </div>
            <div class="con_after">投稿后，我们将在两个工作日内给予反馈，谢谢合作！</div>
            <div class="con_btn">
                <button class="subject-submit button con_a1" data-url="{{route('pc:doSavePost',['type'=>1])}}">存草稿</button>
                <button class="subject-submit button con_a2" data-url="{{route('pc:doSavePost',['type'=>2])}}">投稿</button>
            </div>
        </div>
        <div class="con_right">
            <div class="conR_title">投稿须知</div>
            <div class="conR_artic">
                <p>  1、韩媒集体摆乌龙:美否认三美否认三艘航母下周云集东北亚韩媒集体摆母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
                <p>  2、韩媒集体摆乌龙:美否认三艘航母下周云集东北亚韩媒集体摆乌龙美否认三艘航母下周云集东北亚韩媒集体摆乌龙美否认三艘航母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
                <p>  3、韩媒集体摆乌龙:美否认三艘航母下周云集东北亚韩媒集体摆乌龙:美否认三艘航母下周云集东北亚</p>
            </div>
            <div class="conR_bottom">
                我的草稿<a href=""><span class="conR_num">{{$count}}<i class="icon iconfont icon-icon07"></i></span></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/news.js') }}"></script>
@endsection
