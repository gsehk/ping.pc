<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/assets/images/favicon.png">
</head>

<body class="{{ route_class() }}-page">

    @include('layouts.partials.topnav')

    <section class="mastwrap">

        <div class="container">

            @yield('content')
        </div>

    </section>

    @include('layouts.partials.footer')

    @yield('scripts')
    
</body>

</html>
