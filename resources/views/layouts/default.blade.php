<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title') - {{ $config['app']['name'] or 'ThinkSNS Plus' }}</title>
    <meta name="keywords" content="{{ $config['app']['keywords'] or '' }}"/>
    <meta name="description" content="{{ $config['app']['description'] or '' }}"/>
    <script>
        var TS = {
            API:'{{ $routes["api"] }}',
            USER:{!! json_encode($TS) !!},
            MID: "{{ $TS['id'] or 0 }}",
            TOKEN: "{{ $token or '' }}",
            SITE_URL: "{{ getenv('APP_URL') }}",
            RESOURCE_URL: '{{ asset('assets/pc/') }}',
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
    <script src="{{ mix('global.min.js', 'assets/pc') }}"></script>
    <script src="{{ asset('assets/pc/js/common.js') }}"></script>

    {{-- 环信 --}}
    <script src="{{ mix('easemob.min.js', 'assets/pc') }}"></script>
    <script src="{{ asset('assets/pc/js/module.easemob.js') }}"></script>
    @yield('scripts')

    {{-- 统计代码 --}}
    @if (isset($config['common']['stats_code']) && $config['common']['stats_code'] != '')
    {!! $config['common']['stats_code'] !!}
    @endif
</body>
</html>
