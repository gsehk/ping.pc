    <div class="login_head">
        <div class="header">
            <div class="nav" style=" padding-top: 11px;">
                <div class="nav_left">
                    <a href="{{ route('pc:feeds') }}">
                        <img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" />
                    </a>
                    <!-- <span class="nav_beta fs-16">beta</span> -->
                </div>
                <div class="login_top">
                    @if (route::currentrouteName() == 'pc:login')
                    <a href="{{ route('pc:register') }}" class="fs-16 ">注册</a>
                    @else
                    <a href="{{ route('pc:login') }}" class="fs-16 ">登录</a>
                    @endif
                    <a href="{{ route('pc:feeds') }}" class="fs-16 ">随便看看</a>
                    <a href="http://www.thinksns.com" class="fs-16 ">TS+官网</a>
                </div>
            </div>
        </div>
    </div>
