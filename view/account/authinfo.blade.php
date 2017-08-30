@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')
<div class="account_container">
<div class="account_wrap">

{{-- 左侧导航 --}}
@include('pcview::account.sidebar')
{{-- /左侧导航 --}}

<div class="account_r">
<div class="account_c_c">
<div class="account_tab">
    <div class="perfext_title">
        <select class="J-authenticate-type type" name="type" >
            <option value="user" @if ($info->certification_name == 'user')selected @endif>个人认证</option>
            <option value="org" @if ($info->certification_name == 'org')selected @endif>机构认证</option>
        </select>
    </div>
    {{-- 个人认证 --}}
    @if ($info->certification_name == 'user')
    <div class="user_authenticate" id="J-input-user">
        <div class="account_form_row">
            <label class="w80 required" for="realName">认证状态</label>
            <div>
                @if ($info->status == 0)待审核 @endif
                @if ($info->status == 1)通过 @endif
                @if ($info->status == 2)拒绝 @endif
            </div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="realName">真实姓名</label>
            <div class="account_form_text">{{$info['data']['name']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="IDNumber">身份证号码</label>
            <div class="account_fomr_text">{{$info['data']['number']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="contact">联系方式</label>
            <div class="account_fomr_text">{{$info['data']['phone']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc">认证描述</label>
            <p class="text_box" style="border:0;">{{$info['data']['desc']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required" for="desc">认证资料</label>
            <img class="image" src="{{ $routes['storage'].$info['data']['files'][0] }}">
            <img class="image" src="{{ $routes['storage'].$info['data']['files'][0] }}">
        </div>
    </div>
    @endif
    {{-- /个人认证 --}}

    {{-- 机构认证 --}}
    @if ($info->certification_name == 'org')
    <div class="org_authenticate">
		<div class="account_form_row">
            <label class="w80 required" for="realName">认证状态</label>
            <div>
                @if ($info->status == 0)待审核 @endif
                @if ($info->status == 1)通过 @endif
                @if ($info->status == 2)拒绝 @endif
            </div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">机构名称</label>
            <div>{{$info['data']['org_name']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">机构地址</label>
            <div>{{$info['data']['org_address']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">负责人</label>
            <div>{{$info['data']['name']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">负责人电话</label>
            <div>{{$info['data']['phone']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">营业执照号</label>
            <div>{{$info['data']['number']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">认证描述</label>
            <div class="text_box" style="border:0;">{{$info['data']['desc']}}</div>
        </div>
        <div class="account_form_row">
            <label class="w80 required">认证资料</label>
            <a class="a-link" target="_blank" href="{{ $routes['storage'].$info['data']['files'][0] }}"> 点击查看 </a>
        </div>
    </div>
    @endif
    {{-- /机构认证 --}}
</div>
</div>
</div>
</div>
</div>
@endsection