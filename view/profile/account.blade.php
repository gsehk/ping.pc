@extends('layouts.default')

@section('content')

<div class="success_div">
    
</div>
    
<div class="bas_cont">
    <div class="bas_left">
        <a href="{{Route('pc:account')}}">
            <div class="bas_list">
                <span @if($page == 'account') class="c_333" @endif>资料设置</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-auth'])}}">
            <div class="bas_list">
                <span @if($page == 'account-auth') class="c_333" @endif>认证</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-security'])}}">
            <div class="bas_list">
                <span @if($page == 'account-security') class="c_333" @endif>修改密码</span>
            </div>
        </a>
        <a href="{{Route('pc:account', ['page'=>'account-bind'])}}">
            <div class="bas_list">
                <span @if($page == 'account-bind') class="c_333" @endif>绑定</span>
            </div>
        </a>
    </div>
    <div class="bas_right">
        <div class="bas_header">
            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
            <span>更换头像</span>
        </div>
        <div class="f_div" id="J-input">
            <div class="f_tel bas_div">
                <label>昵称</label>
                <span class="f_span"><input type="text" name="name" value="{{$data['name']}}" placeholder="输入昵称"></span>
            </div>
            <div class="f_tel bas_div">
                <label>简介</label>
                <span class="f_span"><input type="text" name="intro" value="{{$data['intro']}}" placeholder="输入简介"></span>
            </div>
            <div class="f_tel bas_div">
                <label>性别</label>
                  <span class="sex_item"><input name="sex" type="radio" value="1" class="s-ck" @if($data['sex'] == 1) checked="checked" @endif>男</span>
                  <span class="sex_item"><input name="sex" type="radio" value="2" class="s-ck" @if($data['sex'] == 2) checked="checked" @endif>女</span>
                  <span class="sex_item"><input name="sex" type="radio" value="3" class="s-ck" @if($data['sex'] == 3) checked="checked" @endif>不方便透露</span>
            </div>
            <div class="f_tel bas_div">
                <label>生日</label>
                <div class="f_select">
                    <span></span>
                    <select name="y" class="sel_year" rel="2000"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="m" class="sel_month" rel="6"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="d" class="sel_day" rel="14"></select>
                </div>
            </div>
            <div class="f_tel bas_div">
                <label>公司</label>
                <span class="f_span"><input type="text" name="company" placeholder="输入公司名称"></span>
            </div>
            <div class="f_tel bas_div" id="sel_area">
                <label>地区</label>
                <div class="f_select">
                    <span></span>
                    <select name="province" id="province" onchange="getArea(this);"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="city" id="city" onchange="getArea(this);"></select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select name="area" id="area"></select>
                </div>
            </div>
            <a href="javascript:;" class="f_sure" id="J-submit">保存</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/birthday.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/seting.js') }}"></script>
<script> 
var arrSelect = ["{{$data['province']}}", "{{$data['city']}}", "{{$data['area']}}"];
$(function () {
    $.ms_DatePicker({
            YearSelector: ".sel_year",
            MonthSelector: ".sel_month",
            DaySelector: ".sel_day"
    });
    $.ms_DatePicker();
}); 
</script> 
@endsection
