<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>

    <script>
        var TS = {};
        // 公共配置
        TS.API = '{{ $routes["api"] }}';
        TS.TOKEN = "{{ $token or '' }}";
        TS.SITE_URL = "{{ $routes['siteurl'] }}";
        TS.RESOURCE_URL = '{{ $routes["resource"] }}';

        // 登录用户对象
        TS.USER = {!! json_encode($TS) !!};
        TS.MID = TS.USER ? TS.USER['id'] : 0;
        TS.BOOT = {!! json_encode($config['bootstrappers']) !!};
        
        // 转换比例处理
        TS.BOOT['wallet:ratio'] = parseFloat(TS.BOOT['wallet:ratio'] / 100 / 100);
    </script>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/passport.css') }}">
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.min.js') }}"></script>
</head>

<body @yield('body_class')>

    <div class="wrap">
        {{-- 导航 --}}
        @include('pcview::layouts.partials.authnav')

        {{-- 提示框 --}}
        <div class="noticebox authnoticebox">
        </div>

        {{-- 内容 --}}
        <div class="main">
        @yield('content')
        </div>
    </div>

    {{-- 底部 --}}
    @include('pcview::layouts.partials.authfooter')

    <script src="{{ asset('zhiyicx/plus-component-pc/js/common.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/font/iconfont.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.cookie.js') }}"></script>
    @yield('scripts')

    {{-- 统计代码 --}}
    @if (isset($config['common']['stats_code']) && $config['common']['stats_code'] != '')
    {!! $config['common']['stats_code'] !!}
    @endif
</body>

</html>
