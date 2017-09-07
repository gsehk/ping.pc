<div class="nav nav_border">
    <div class="nav_left">
        <a href="{{ route('pc:feeds') }}"><img src="{{ $routes['resource'] }}/images/logo.png" class="nav_logo" /></a>
        <!-- <span class="nav_beta fs-16">beta</span> -->
    </div>

    @if (!empty($TS))
    <div class="nav_right">
        <img src="{{ $TS['avatar'] or $routes['resource'] . '/images/avatar.png' }}" id="menu_toggle" alt="{{ $TS['name'] }}"/>
        <span class="fs-16 nav_name">{{$TS['name']}}</span>

        <div class="nav_menu">
            <div class="hover_cover clearfix">
            </div>
            <ul>
                {{-- <li>
                    <a href="{{ route('pc:collect') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-shoucang-copy"></use></svg>我的收藏
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('pc:authenticate') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-renzheng3-copy"></use></svg>去认证
                    </a>
                </li>
                <li>
                    <a href="{{ route('pc:account') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-shezhi-copy1"></use></svg>设置
                    </a>
                </li>
                <li>
                    <a href="{{ route('pc:account') }}">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-qianbao"></use></svg>我的钱包
                    </a>
                </li>
                <li style="border-top: 1px solid #ededed; padding-top: 20px;">
                    <a href="{{ route('pc:mine')}}">个人主页</a>
                </li>
                <li>
                    <a href="javascript:void(0)" onclick="logout()">退出</a>
                </li>
            </ul>
            <img src="{{ $routes['resource'] }}/images/triangle.png" class="triangle" />
        </div>
    </div>
    @else
    <div class="nav_right">
        <a class="nava" href="{{ route('pc:register') }}">注册</a>
        <a class="nava" href="{{ route('pc:login') }}">登录</a>
    </div>
    @endif
    
    <div class="nav_list clearfix">
        <ul class="navs">
            <li><a href="{{ route('pc:feeds') }}" @if(!empty($current) && $current == 'feeds') class="selected" @endif>动态</a></li>
            <li><a href="{{ route('pc:group') }}" @if(!empty($current) && $current == 'group') class="selected" @endif>圈子</a></li>
            <li><a href="{{ route('pc:news') }}" @if(!empty($current) && $current == 'news') class="selected" @endif>资讯</a></li>
            <li><a href="{{ route('pc:users') }}" @if(!empty($current) && $current == 'users') class="selected" @endif>找伙伴</a></li>
        </ul>

        <div class="nav_search">
            <input class="nav_input" type="text" placeholder="输入关键词搜索" value="{{ $keywords or ''}}" id="head_search"/>
            <a class="nav_search_icon">
                <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-sousuo"></use></svg>
            </a>

            <div class="head_search">
                <!-- 搜索历史记录 -->
                <div class="history">
                    <p>历史记录</p>
                    <ul></ul>
                    <div class="clear">
                        <a href="javascript:;" onclick="delHistory('all')">清空历史记录</a>
                    </div>
                </div>

                <!-- 搜索类型 -->
                <div class="search_types">
                    <ul>
                        <li type="1"><span>与<span class="keywords"></span>相关的动态</span></li>
                        <!-- <li type="2"><span>与<span class="keywords"></span>相关的问答</span></li> -->
                        <li type="3"><span>与<span class="keywords"></span>相关的文章</span></li>
                        <li type="4"><span>与<span class="keywords"></span>相关的用户</span></li>
                        <li type="5"><span>与<span class="keywords"></span>相关的圈子</span></li>
                        <!-- <li type="6"><span>与<span class="keywords"></span>相关的活动</span></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

