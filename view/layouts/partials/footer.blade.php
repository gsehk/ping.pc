<div class="footer">
    <div class="footer_cont">
        @if (isset($config['nav_bottom']) && !$config['nav_bottom']->isEmpty())
        <ul>
            @foreach ($config['nav_bottom'] as $nav)
            <li>
                <a target="{{ $nav->target }}" href="{{ $nav->url }}">{{ $nav->name}} </a>
            </li>
            @endforeach
        </ul>
        @endif
        <div class="rights font12">Powered by ThinkSNS ©2017 ZhishiSoft All Rights Reserved.</div>
        <div class="developer">本站/APP由 <span>ThinkSNS+</span> 提供技术和产品支持</div>
    </div>
</div>
