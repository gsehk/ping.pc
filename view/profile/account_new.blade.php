@extends('pcview::layouts.default')
<link rel="stylesheet" href="{{ $routes['resource'] }}/css/account.css" />
<link rel="stylesheet" href="{{ $routes['resource'] }}/css/iconfont/iconfont.css" /> @section('bgcolor')style="background-color:#f3f6f7"@endsection @section('content')
<div class="account_container">
    <div class="account_wrap">
        <div class="account_l">
            <ul class="account_menu">
                <li class="active"><i class="iconfont icon-ziliao"></i>基本资料</li>
                <li><i class="iconfont icon-ziliao"></i>标签管理</li>
                <li><i class="iconfont icon-ziliao"></i>我的钱包</li>
                <li><i class="iconfont icon-ziliao"></i>会员管理</li>
                <li><i class="iconfont icon-ziliao"></i>认证管理</li>
                <li><i class="iconfont icon-ziliao"></i>隐私设置</li>
                <li><i class="iconfont icon-ziliao"></i>通知设置</li>
                <li><i class="iconfont icon-ziliao"></i>黑名单</li>
                <li><i class="iconfont icon-ziliao"></i>安全设置</li>
                <li><i class="iconfont icon-ziliao"></i>账号管理</li>
            </ul>
        </div>
        <div class="account_r">
            <div class="account_c_c">
                <!-- 标签管理 -->
                <div class="account_tab">
                    <!-- label -->
                    <div class="perfext_title">
                        <p>选择标签</p>
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
                        </ul>
                    </div>
                    <div class="perfect_row">
                        <label>身份</label>
                        <ul class="perfect_label_list">
                            <li>酱油君</li>
                            <li>个人站长</li>
                            <li>企业用户</li>
                            <li>个人站长</li>
                            <li>企业用户</li>
                            <li>创客</li>
                        </ul>
                    </div>
                    <!-- /list -->
                    <!-- select -->
                    <div class="perfect_selected">
                        <label>最多可选
                            <span class="total">5</span>个标签，已选择
                            <span class="cur_count">0</span>个</label>
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
                    </div>
                    <!-- /btn -->
                </div>
                <!-- /标签管理 -->

                <!-- 基本资料 -->
                <div class="account_tab">
                    <!-- label -->
                    <div class="perfext_title">
                        <p>基本资料</p>
                    </div>
                    <!-- /label -->
                    <!-- 更改头像 -->
                    <div class="perfect_row mb30">
                        <div class="account_heder">
                            <div class="header">
                                <img src="http://blog.jsonleex.com/icon/LX.png" alt="用户头像">
                            </div>
                            <a class="perfect_btn" href="javascript:;">更改头像</a>
                        </div>
                    </div>
                    <!-- /更改头像 -->
                    <!-- txt -->
                    <div class="perfect_row mb30">
                        <form action="#">
                            <div class="account_form_row">
                                <label for="name">昵称</label>
                                <input name="name" id="name" type="text">
                            </div>
                            <div class="account_form_row">
                                <label for="bio">简介</label>
                                <input name="bio" id="bio" type="text">
                            </div>
                            <div class="account_form_row">
                                <label>性别</label>
                                <div class="input">
                                    <span><input id="male" name="sex" type="radio"><label for="male">男</label></span>
                                    <span><input id="female" name="sex" type="radio"><label for="female">女</label></span>
                                    <span><input checked id="secret" name="sex" type="radio"><label for="secret">不方便透露</label></span>
                                </div>
                            </div>
                            <div class="account_form_row">
                                <label>生日</label>
                                <div class="input">
                                    <select name="year" id="year">
                                        <option value="2017">2017</option>
                                    </select>
                                    <select name="month" id="month">
                                        <option value="02">2月</option>
                                    </select>
                                    <select name="day" id="day">
                                        <option value="26">26日</option>
                                    </select>
                                </div>
                            </div>
                            <div class="account_form_row">
                                <label for="company">公司</label>
                                <input name="company" id="company" type="text">
                            </div>
                            <div class="account_form_row">
                                <label for="area">地区</label>
                                <input name="area" id="area" type="text" placeholder="输入居住地">
                                <span class="dinwei loading"></span>
                            </div>
                        </form>
                    </div>
                    <!-- /txt -->
                    <div class="perfect_btns">
                        <a href="javascript:;" class="perfect_btn save" id="save">保存</a>
                    </div>
                </div>
                <!-- /基本资料 -->

                <!-- 修改密码 -->
                <div class="account_tab">
                    <!-- label -->
                    <div class="perfext_title">
                        <p>修改密码</p>
                    </div>
                    <!-- /label -->
                    <!-- form  -->
                    <div class="account_form_row">
                        <label class="w80 required" for="oldPsw">原密码</label>
                        <input name="oldPsw" id="oldPsw" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="newPsw">设置新密码</label>
                        <input name="newPsw" id="newPsw" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="newPsw2">确认新密码</label>
                        <input name="newPsw2" id="newPsw2" type="text">
                    </div>
                    <!-- /form  -->
                    <!-- btn -->
                    <div class="perfect_btns">
                        <a href="javascript:;" class="perfect_btn save" id="save">保存</a>
                    </div>
                    <!-- /btn -->
                </div>
                <!-- /修改密码 -->
            </div>
        </div>
    </div>
</div>
@endsection