<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>
    <script>
        // 全局变量
        var PUBLIC_URL = '{{\Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset("")}}';
        var API = '/api/v1';
        // 用户属性
        var MID = "{{$TS['id'] or 0}}";
        var NAME = "{{$TS['name'] or ''}}";
        var AVATAR = "{{$TS['avatar'] or \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/avatar.png')}}";
        var TOKEN = "{{$TS['token'] or ''}}";
    </script>
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/font/iconfont.css') }}" rel="stylesheet">
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.min.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/font/iconfont.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/common.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.pjax.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/nprogress.js') }}"></script>
</head>
<body @yield('body_class')>

    <!-- nav -->
    @include('layouts.partials.nav')

    <!-- content -->
    @yield('content')
    
    <!-- footer -->
    @include('layouts.partials.footer')

    <!-- js -->
    @yield('scripts')

</body>
<script src="http://cdn.bootcss.com/scrollup/2.4.1/jquery.scrollUp.min.js"></script>
</html>
