@extends('layouts.default')

@section('content')
<div class="dy_bg">
    <div class="con_cont">
        <div class="con_left">
            <div class="con_title">
                <input type="text" placeholder="请在此输入20字以内的标题" />
            </div>
            <div class="con_title p_30">
                <input type="text" placeholder="请在此输入20字以内的标题" />
            </div>
            <div class="con_place">
                <textarea placeholder="代替内容"></textarea>
            </div>
            <div class="con_produce">
                <span class="con_bq">
                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/pro.png') }}" /><input placeholder="添加标签，多个标签用逗号分开" />
                </span>
                <span class="con_cover">
                    封面555*393px
                    <a href="#">点击上传</a>
                </span>
            </div>
            <div class="con_word">
                <input placeholder="文章转载至何处（非转载可不填）" />
            </div>
            <div class="con_after">投稿后，我们将在两个工作日内给予反馈，谢谢合作！</div>
            <div class="con_btn">
                <a href="javascript:;" class="con_a1">存草稿</a>
                <a href="javascript:;" class="con_a2">投稿</a>
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
                我的草稿
                <i class="icon iconfont icon-icon07"></i>
                <span class="conR_num">12</span>
            </div>
        </div>
    </div>
</div>
@endsection
