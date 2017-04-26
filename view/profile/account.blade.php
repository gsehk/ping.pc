@extends('layouts.default')

@section('content')

<div class="success_div">
    <div class="set_success s_bg">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/set_success.png') }}" />资料修改成功
    </div>
    <div class="set_success">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/failure.png') }}" />资料修改失败
    </div>
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
        <div class="f_div">
            <div class="f_tel bas_div">
                <label>昵称</label>
                <span class="f_span"><input type="text" placeholder="输入昵称" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div">
                <label>简介</label>
                <span class="f_span"><input type="text" placeholder="输入简介" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div">
                <label>性别</label>
                <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>男</span>
                <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>女</span>
                <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>不方便透露</span>
            </div>
            <div class="f_tel bas_div">
                <label>生日</label>
                <div class="f_select">
                    <span></span>
                    <select>
                        <option>2017</option>
                        <option>2016</option>
                        <option>1993</option>
                    </select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select>
                        <option>8</option>
                        <option>5</option>
                        <option>2</option>
                    </select>
                </div>
                <div class="f_select">
                    <span> </span>
                    <select>
                        <option>11</option>
                        <option>10</option>
                        <option>1</option>
                    </select>
                </div>
            </div>
            <div class="f_tel bas_div">
                <label>公司</label>
                <span class="f_span"><input type="text" placeholder="输入公司名称" style="color:#ccc"></span>
            </div>
            <div class="f_tel bas_div">
                <label>地区</label>
                <div class="f_select">
                    <span></span>
                    <select>
                        <option>北京</option>
                        <option>成都</option>
                        <option>重庆</option>
                    </select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select>
                        <option>北京</option>
                        <option>成都</option>
                        <option>重庆</option>
                    </select>
                </div>
                <div class="f_select">
                    <span></span>
                    <select>
                        <option>北京</option>
                        <option>成都</option>
                        <option>重庆</option>
                    </select>
                </div>
            </div>
            <a href="#" class="f_sure">保存</a>
        </div>
    </div>
</div>
@endsection
