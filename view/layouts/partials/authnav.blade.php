    <div class="login_head">
        <div class="header">
            <div class="nav" style=" padding-top: 11px;">
                <div class="nav_left">
                    <a href="{{ route('pc:feed') }}">
                        <!-- <img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" /> -->
                        <img src="{{ $routes['resource'] }}/jinronghu/logo.png" class="nav_logo" />
                    </a>
                    <!-- <span class="nav_beta fs-16">beta</span> -->
                </div>
                <div class="login_top">
                    @if (route::currentrouteName() == 'pc:index')
                    <a href="{{ route('pc:register') }}" class="fs-16 ">注册</a>
                    @else
                    <a href="{{ route('pc:index') }}" class="fs-16 ">登录</a>
                    @endif
                    <a href="{{ route('pc:feed') }}" class="fs-16 ">随便看看</a>
                    <a href="http://www.thinksns.com" class="fs-16 ">TS+官网</a>
                </div>
            </div>
        </div>
    </div>
