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
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.footer')

    <!-- js -->
    @yield('scripts')

</body>
<script src="http://cdn.bootcss.com/scrollup/2.4.1/jquery.scrollUp.min.js"></script>
</html>
