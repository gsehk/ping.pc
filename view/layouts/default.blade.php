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
        var SOCKET = "{{ $TS['routes']['socket'] or ''}}"
    </script>
    <link href="{{ $routes['resource'] }}/css/main.css" rel="stylesheet">
    <link href="{{ $routes['resource'] }}/css/message.css" rel="stylesheet">
    <link href="{{ $routes['resource'] }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $routes['resource'] }}/css/font/iconfont.css" rel="stylesheet">
    <script src="{{ $routes['resource'] }}/js/jquery.min.js"></script>
    <script src="{{ $routes['resource'] }}/layer/layer.js"></script>
    <script src="{{ $routes['resource'] }}/js/jquery.lazyload.min.js"></script>
    <script src="{{ $routes['resource'] }}/js/font/iconfont.js"></script>
    <script src="{{ $routes['resource'] }}/js/common.js"></script>
    <script src="{{ $routes['resource'] }}/js/module.message.js"></script>
</head>

<body @yield('body_class')>
<script type="text/javascript">
    message.init();
</script>
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
        <div class="qq_mes" style="z-index:9999;position:fixed;width:140px;height:220px;background:#fff;text-align:center;top:300px;left:10px;">
          <a href="tencent://message/?uin=3298713109&amp;Site=121ask.com&amp;Menu=yes"><img style="margin-top:18px" src="http://demo.thinksns.com/ts4/resources/theme/stv1/_static/image/qq.png"></a>
          <span style="display:block"><a href="tencent://message/?uin=3298713109&amp;Site=121ask.com&amp;Menu=yes">QQ在线咨询</a></span>
          <div style="border-top:1px solid #e3e4e5;margin:0 auto;width:120px;padding:20px 0px;margin-top:18px;">
            <a href="http://www.thinksns.com" target="_blank"><span style="font-size:18px;color:#0096e6">ThinkSNS官网</span></a>
          </div>
        </div>
    </div>

    <!-- footer -->
    @include('pcview::layouts.partials.footer')

    <!-- js -->
    @yield('scripts')

</body>
</html>
