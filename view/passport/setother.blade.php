@extends('layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
<div class=" forget_cont" style="height:640px;">
    <div class="f_welcome">欢迎您，xxx，您的QQ互联账号授权成功！</div>
    <div class=f_next>接下来完成简单的设置，即可用QQ互联账号直接登录。</div>
    <div class="f_div">
        <div class="f_tel">
            <label>昵称</label>
            <span class="f_span"><input type="text" placeholder="限2-10个字符" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>邮箱</label>
            <span class="f_span"><input type="text" placeholder="常用的电子邮箱" style="color:#ccc" /></span>
        </div>
        <div class="f_tel ">
            <label>密码</label>
            <span class="f_span w_370"><input type="text" placeholder="设置登录密码" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>确认密码</label>
            <span class="f_span"><input type="text" placeholder="再次输入密码" style="color:#ccc" /></span>
        </div>
        <div class="f_tel">
            <label>性别</label>
            <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>男</span>
            <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>女</span>
            <span class="f_sex"><i class="icon iconfont icon-xuanze"></i>不方便透露</span>
        </div>
        <div class="f_tel">
            <label>地区</label>
            <div class="f_select">
                <span><!--<img src="../img/down.png" />--> </span>
                <select>
                    <option>北京</option>
                    <option>成都</option>
                    <option>重庆</option>
                </select>
            </div>
            <div class="f_select">
                <span><!--<img src="../img/down.png" />--> </span>
                <select>
                    <option>北京</option>
                    <option>成都</option>
                    <option>重庆</option>
                </select>
            </div>
            <div class="f_select">
                <span><!--<img src="../img/down.png" />--> </span>
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
@endsection

