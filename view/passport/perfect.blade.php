@section('title') 选择标签 @endsection @extends('pcview::layouts.auth') @section('body_class')class="gray"@endsection @section('content')
<div class="perfect_container">
    <div class="perfect_wrap">
        <!-- label -->
        <div class="perfext_title">
            <p>选择标签</p>
            <span>标签为全局标签，选择合适的标签，系统可推荐你感兴趣的内容，方便找到相同身份或爱好的人，很重要哦！</span>
        </div>
        <!-- /label -->

        <!-- list -->
        <div class="perfect_row">
            <label>身份</label>
            <ul class="perfect_label_list">
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
            </ul>
        </div>
        <div class="perfect_row">
            <label>身份</label>
            <ul class="perfect_label_list">
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
            </ul>
        </div>
        <div class="perfect_row">
            <label>身份</label>
            <ul class="perfect_label_list">
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
                <li>酱油君</li>
                <li>个人站长</li>
                <li>企业用户</li>
                <li>创客</li>
            </ul>
        </div>
        <!-- /list -->
        
        <!-- select -->
        <div class="perfect_selected">
            <label>最多可选<span class="total">5</span>个标签，已选择<span class="cur_count">0</span>个</label>
            <ul class="perfect_selected_list">
                <li>建筑师<i class="icon close">x</i></li>
                <li>旅行家<i class="icon close">x</i></li>
                <li>运动达人<i class="icon close">x</i></li>
            </ul>
        </div>
        <!-- /select -->

        <!-- btn -->
        <div class="perfect_btns">
            <a href="javascript:;" class="perfect_btn save" id="save">保存</a>
            <a href="javascript:;" class="perfect_btn skip" id="skip">跳过</a>
        </div>
        <!-- /btn -->
    </div>
</div>
@endsection @section('scripts')
<script src="{{ $routes['resource'] }}/js/jquery.form.js"></script>
<script src="{{ $routes['resource'] }}/js/module.passport.js"></script>
@endsection