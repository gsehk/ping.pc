@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>

    <script>
        var API = '{{ $routes["api"] }}';
        var MID = "{{ $TS['id'] or 0 }}";
        var NAME = "{{ $TS['name'] or '' }}";
        var AVATAR = "{{ $TS['avatar'] }}";
        var TOKEN = "{{ $token or '' }}";
        var SITE_URL = "{{ $routes['siteurl'] }}";
        var RESOURCE_URL = '{{ $routes["resource"] }}';
        var SOCKET_URL = "{{ $routes['socket_url'] or ''}}";
    </script>
    <link href="{{ $routes['resource'] }}/css/common.css" rel="stylesheet">
    <script src="{{ $routes['resource'] }}/js/font/iconfont.js"></script>
    <script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>
    <script src="{{ $routes['resource'] }}/js/jquery.lazyload.min.js"></script>
    <script src="{{ $routes['resource'] }}/layer/layer.js"></script>
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
        <div class="main" @yield('bgcolor')>
            <div class="container clearfix">
            @yield('content')
            </div>
        </div>

        <div class="dy_calltop">
            <a href="#" class="dy_1">  <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shouji"></use></svg></a>
            <a href="javascript:;" class="dy_2" id="gotop"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-uptop"></use></svg></a>
        </div>
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.footer')

    <!-- js -->
    @yield('scripts')
</body>
</html>
