<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('css/font/iconfont.css') }}" rel="stylesheet">
    <script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/font/iconfont.js') }}"></script>
</head>

<body @yield('body_class')>

    <!-- nav -->
    @include('layouts.partials.topnav')

    <!-- content -->
    @yield('content')
    
    <!-- footer -->
    @include('layouts.partials.footer')

    <!-- js -->
    @yield('scripts')
    
</body>

</html>
