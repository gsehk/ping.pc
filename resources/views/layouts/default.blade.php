<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>
    <script>
        var TS = {
            API:'{{ $routes["api"] }}',
            USER:{!! json_encode($TS) !!},
            MID: "{{ $TS['id'] or 0 }}",
            TOKEN: "{{ $token or '' }}",
            SITE_URL: "{{ $routes['siteurl'] }}",
            RESOURCE_URL: '{{ $routes["resource"] }}',
            EASEMOB_KEY: {!! json_encode($config['easemob_key']) !!},
            BOOT: {!! json_encode($config['bootstrappers']) !!},
            UNREAD: {}
        };
    </script>
    <link rel="stylesheet" href="{{ asset('assets/pc/css/common.css') }}">
    <script src="{{ asset('assets/pc/js/jquery.min.js') }}"></script>
    @yield('styles')
</head>

<body @yield('body_class')>
    <div class="wrap">
        {{-- 导航 --}}
        @include('pcview::layouts.partials.nav')

        {{-- 提示框 --}}
        <div class="noticebox">
        </div>

        {{-- 内容 --}}
        <div class="main">
            <div class="container @yield('extra_class') clearfix">
            @yield('content')
            </div>
        </div>

        <div class="right_extras">
            <a href="#" class="app">  <svg class="icon" aria-hidden="true"><use xlink:href="#icon-phone"></use></svg></a>
            <a href="javascript:;" class="gotop" id="gotop"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-uptop"></use></svg></a>
        </div>
    </div>

    {{-- 底部 --}}
    @include('pcview::layouts.partials.footer')

    {{-- 二维码 --}}
    <div class="weixin_qrcode">
    </div>
    <script src="{{ asset('assets/pc/js/axios.min.js')}}"></script>
    <script src="{{ asset('assets/pc/js/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/pc/js/common.js') }}"></script>
    <script src="{{ asset('assets/pc/js/font/iconfont.js') }}"></script>
    <script src="{{ asset('assets/pc/js/jquery.lazyload.min.js') }}"></script>
    <script src="{{ asset('assets/pc/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/pc/layer/layer.js') }}"></script>
    <script src="{{ asset('assets/pc/js/dexie.min.js') }}"></script>

    {{-- 环信 --}}
    <script src="{{ asset('assets/pc/js/easemob/webim.config.js') }}"></script>
    <script src="{{ asset('assets/pc/js/easemob/strophe-1.2.8.min.js') }}"></script>
    <script src="{{ asset('assets/pc/js/easemob/websdk-1.4.13.js') }}"></script>
    <script src="{{ asset('assets/pc/js/module.easemob.js') }}"></script>
    @yield('scripts')

    {{-- 统计代码 --}}
    @if (isset($config['common']['stats_code']) && $config['common']['stats_code'] != '')
    {!! $config['common']['stats_code'] !!}
    @endif
</body>
</html>
