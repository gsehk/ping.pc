<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/font/iconfont.css') }}" rel="stylesheet">
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.min.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/font/iconfont.js') }}"></script>
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/module.js') }}"></script>
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
