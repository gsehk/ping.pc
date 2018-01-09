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
            COMMON: {!! json_encode($config['common']) !!},
            BOOT: {!! json_encode($config['bootstrappers']) !!},
            UNREAD: {},// 未读消息数量
        };
    </script>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/common.css') }}">
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.min.js') }}"></script>
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
    <script src="{{ asset('zhiyicx/plus-component-pc/js/axios.min.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/common.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/lodash.min.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/font/iconfont.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.lazyload.min.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/layer/layer.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/dexie.min.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.message.js') }}"></script>
    @yield('scripts')

    {{-- 统计代码 --}}
    @if (isset($config['common']['stats_code']) && $config['common']['stats_code'] != '')
    {!! $config['common']['stats_code'] !!}
    @endif
</body>
</html>
