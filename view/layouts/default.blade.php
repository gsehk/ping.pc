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
        TS.COMMON = {!! json_encode($config['common']) !!};
        // 转换比例处理
        TS.BOOT['wallet:ratio'] = parseFloat(TS.BOOT['wallet:ratio'] / 100 / 100);
        // 未读消息数量
        TS.UNREAD = {};
    </script>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/common.css') }}">
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.min.js') }}"></script>
    @yield('styles')
</head>

<body @yield('body_class')>
    <div class="wrap">
        <!-- nav -->
        @include('pcview::layouts.partials.nav')

        <!-- noticebox -->
        <div class="noticebox">
        </div>

        <!-- content -->
        <div class="main">
            <div class="container @yield('extra_class') clearfix">
            @yield('content')
            </div>
        </div>

        <div class="right_extras">
            <a href="#" class="app">  <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shouji"></use></svg></a>
            <a href="javascript:;" class="gotop" id="gotop"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-uptop"></use></svg></a>
        </div>
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.footer')

    <script src="{{ asset('zhiyicx/plus-component-pc/js/common.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/lodash.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/font/iconfont.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.lazyload.min.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/layer/layer.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/dexie.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.message.js') }}"></script>

    @yield('scripts')
</body>
</html>
