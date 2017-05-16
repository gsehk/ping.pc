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
        var TOKEN = "{{$TS['token'] or '1'}}";
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
    @include('layouts.partials.authnav')

    <!-- content -->
    @yield('content')
    
    <!-- footer -->
    @include('layouts.partials.authfooter')

    <!-- js -->
    @yield('scripts')
    
</body>

</html>
