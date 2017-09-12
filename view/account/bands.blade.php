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

                    <div class="band-box">
                        <div class="mb30 band-line">
                            <div class="band-left">绑定手机</div>
                            <div class="band-right {{$phone ?: 'blue'}}">{{$phone ? '已绑定' : '去绑定'}}</div>
                        </div>

                        <div class="mb30 band-line">
                            <div class="band-left">绑定邮箱</div>
                            <div class="band-right {{$email ?: 'blue'}}">{{$email ? '已绑定' : '去绑定'}}</div>
                        </div>

                        <div class="mb30 band-line">
                            <div class="band-left">绑定QQ</div>
                            <div class="band-right {{$qq ?: 'blue'}}">{{$qq ? '已绑定' : '去绑定'}}</div>
                        </div>

                        <div class="mb30 band-line">
                            <div class="band-left">绑定微信</div>
                            <div class="band-right {{$wechat ?: 'blue'}}">{{$wechat ? '已绑定' : '去绑定'}}</div>
                        </div>

                        <div class="mb30 band-line">
                            <div class="band-left">绑定微博</div>
                            <div class="band-right {{$weibo ?: 'blue'}}">{{$weibo ? '已绑定' : '去绑定'}}</div>
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

    </script>
@endsection