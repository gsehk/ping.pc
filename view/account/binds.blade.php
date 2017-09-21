@section('title')
    账号管理
@endsection

@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/cropper/cropper.min.css')}}">
@endsection

@section('content')
    <div class="account_container">

        @include('pcview::account.sidebar')

        <div class="account_r">
            <div class="account_c_c">
                <div class="account_tab" id="J-input">
                    <div class="perfect_title">
                        <p>账号管理</p>
                    </div>

                    <div class="bind-box">
                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定手机</div>
                            @if($phone)
                                <a class="bind-right">已绑定</a>
                            @else
                                <a class="bind-right blue to_bind" data-type="phone">去绑定</a>
                            @endif
                            {{--<div class="bind-right {{$phone ?: 'blue'}}">{{$phone ? '已绑定' : '去绑定'}}</div>--}}

                            <div class="bind-content">
                                <div class="bind_form_row">
                                    <label for="mobile">手机号</label>
                                    <input id="mobile" name="mobile" type="text" value="">
                                    <a id="send_code" href="javascript:">获取验证码</a>
                                </div>
                                <div class="bind_form_row">
                                    <label for="rg_code">验证码</label>
                                    <input id="rg_code" name="rg_code" type="text" value="">
                                </div>
                                <div class="bind_form_row">
                                    <label for="password">密码</label>
                                    <input id="password" name="password" type="password" value="">
                                </div>
                                <a class="bind-submit" href="javascript:">确定</a>
                            </div>


                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定邮箱</div>
                            <div class="bind-right {{$email ?: 'blue'}}">{{$email ? '已绑定' : '去绑定'}}</div>
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定QQ</div>
                            @if($qq)
                                <div class="bind-right remove" data-type="qq" data-bind="1">已绑定</div>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/qq/bind'}}">去绑定</a>
                            @endif
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定微信</div>
                            @if($wechat)
                                <div class="bind-right remove" data-type="wechat" data-bind="1">已绑定</div>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/wechat/bind'}}">去绑定</a>
                            @endif
                        </div>

                        <div class="mb30 bind-line">
                            <div class="bind-left">绑定微博</div>
                            @if($weibo)
                                <a class="bind-right remove" data-type="weibo" data-bind="1">已绑定</a>
                            @else
                                <a class="bind-right blue" href="{{route('pc:socialitebind').'/weibo/bind'}}">去绑定</a>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/cropper/cropper.min.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.account.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
    <script>
        // 绑定手机页面
        $('.bind-box').on('click', 'a.to_bind', function () {
            var _this = $(this);
            var type = _this.data('type');
            if (type == 'phone') {
                _this.siblings('.bind-content').show('fast');
                _this.addClass('hide');
                return false;
            }
        });

        $('#mobile').on('keyup', function () {
            if((/^1(3|4|5|7|8)\d{9}$/.test($(this).val()))){
                $('#send_code').addClass('blue-color');
            } else {
                $('#send_code').hasClass('blue-color') && $('#send_code').removeClass('blue-color');
            }
        });

        // 移除绑定信息 qq/wechat/weibo
        $('.bind-box').on('click', 'a.remove', function () {
            var _this = $(this);
            var type = _this.data('type');

            if (_this.data('bind') == 1) {
                var url = '/api/v2/user/socialite/'+type;
                $.ajax({
                    url: SITE_URL + url,
                    type: 'DELETE',
                    data: {},
                    dataType: 'json',
                    error: function (xml) {
                    },
                    success: function (res, data, xml) {
                        if (xml.status == 204) {
                            _this.removeClass('remove').addClass('blue').text('去绑定');
                            _this.removeAttr('data-type').removeAttr('data-bind');
                            _this.attr('href', SITE_URL + '/socialite/'+type+'/bind');

                            noticebox('操作成功', 1);
                        } else {
                            noticebox(res.message, 0);
                        }

                        return false;
                    }
                });

            }
        });

    </script>
@endsection