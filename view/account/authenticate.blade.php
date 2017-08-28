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

        <div class="account_r">
            <div class="account_c_c">
                <!-- 个人认证 -->
                <div class="account_tab" id="J-input">
                    <!-- label -->
                    <div class="perfext_title">
                        <p>个人认证</p>
                    </div>
                    <!-- /label -->
                    <!-- form  -->
                    <div class="account_form_row">
                        <label class="w80 required" for="realName">真实姓名</label>
                        <input id="realName" name="realname" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="IDNumber">身份证号码</label>
                        <input  id="IDNumber" name="idcard" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="contact">联系方式</label>
                        <input id="contact" name="phone" type="text">
                    </div>
                    <div class="account_form_row">
                        <label class="w80 required" for="desc">认证描述</label>
                        <input id="desc" name="info" type="text">
                    </div>
					<div class="account_form_row">
                        <label class="w80 required" for="desc">认证资料</label>
						<input id="J-file-upload"
                           name="file"
                           type="file"
                           data-input="#task_id"
                           data-preview="#J-image-preview"
                           data-token="{{ csrf_token() }}"
                    >
                        <input name="task_id" id="task_id" type="hidden" value="" />
                    </div>
                    <!-- /form  -->
                    <!-- btn -->
                    <div class="perfect_btns">
                        <a class="perfect_btn save" id="J-user-authenticate" data-url="{{ Route('pc:doSaveAuth') }}" href="javascript:;">保存</a>
                    </div>
                    <!-- /btn -->
                </div>
                <!-- /个人认证 -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>

</script>
@endsection