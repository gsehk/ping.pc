<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>

    <script>
        // 全局变量
        var PUBLIC_URL = '{{ $routes["resource"] }}';
        var API = '/api/v1/';
        // 用户属性
        var MID = "{{ $TS['id'] or 0 }}";
        var NAME = "{{ $TS['name'] or '' }}";
        var AVATAR = "{{ $TS['avatar'] }}";
        var TOKEN = "{{ $TS['token'] or '' }}";
    </script>
    <link href="{{ $routes['resource'] }}/css/main.css" rel="stylesheet">
    <link href="{{ $routes['resource'] }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $routes['resource'] }}/css/font/iconfont.css" rel="stylesheet">
    <script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>
    <script src="{{ $routes['resource'] }}/layer/layer.js"></script>
    <script src="{{ $routes['resource'] }}/js/jquery.lazyload.min.js"></script>
    <script src="{{ $routes['resource'] }}/js/font/iconfont.js"></script>
    <script src="{{ $routes['resource'] }}/js/common.js"></script>
</head>

<body @yield('body_class')>
    <div class="wrap">
        <!-- nav -->
        @include('pcview::layouts.partials.nav')

        <!-- noticebox -->
        <div class="noticebox">
        </div>

        <!-- content -->
        <div class="dy_bg" @yield('bgcolor')>
        @yield('content')
        </div>

        <div class="dy_calltop">
            <a href="https://fir.im/ThinkSNSPlus" class="dy_1">  <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shouji"></use></svg></a>
            <a href="javascript:;" class="dy_2" id="gotop"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-uptop"></use></svg></a>
        </div>
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.footer')

    <!-- js -->
    @yield('scripts')

</body>
</html>
