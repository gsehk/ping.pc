@section('title')
    三方{{$type == 0 ? '注册' : '绑定'}}
@endsection

@extends('pcview::layouts.auth')

@section('body_class')class="gray"@endsection

@section('content')
    <div class="reg_cont" style="height:640px;">
        <ul class="reg_menu">
            <li><a href="{{ route('pc:oauthuser') }}" @if($type == 0) class="current" @endif>新用户注册</a></li>
            <li><a href="{{ route('pc:oauthuser', ['type'=>1]) }}" @if($type == 1) class="current" @endif>绑定账号</a></li>
        </ul>

        @if($type == 0)
            <div class="reg_form">
                <form method="POST" id="auth_form">
                    <div class="reg_input">
                        <label>设置用户名</label>
                        <span class="input_span">
                            <input type="text" placeholder="2-10个字符" name="name" value="{{$name}}"/>
                        </span>
                    </div>

                    <input type="hidden" name="access_token" value="{{$access_token}}">
                    <input type="hidden" name="other_type" value="{{$other_type}}">
                    <input type="hidden" name="verifiable_type" value="register">

                    <a id="oauth_btn" class="reg_btn">确定</a>
                </form>
            </div>
        @else
            <div class="reg_form">
                <form method="POST" id="auth_form">

                    <div class="reg_input">
                        <label>账号</label>
                        <span class="input_span">
                            <input type="text" placeholder="手机/邮箱/用户名" name="login" />
                        </span>
                    </div>
                    <div class="reg_input">
                        <label>密码</label>
                        <span class="input_span">
                            <input type="password" placeholder="输入正确的密码" name="password" />
                        </span>
                    </div>

                    <input type="hidden" name="access_token" value="{{$access_token}}">
                    <input type="hidden" name="other_type" value="{{$other_type}}">
                    <input type="hidden" name="verifiable_type" value="bind">

                    <a id="oauth_btn" class="reg_btn">确定</a>
                </form>
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script src="{{ $routes['resource'] }}/js/jquery.form.js"></script>
    <script src="{{ $routes['resource'] }}/js/module.socialite.js"></script>
@endsection
