
<!-- 右侧广告位 -->
@if ($type == 1)

    @if(!$ads->isEmpty())
        @foreach($ads as $ad)
        <div class="news_ad">
            <a href="{{ $ad['link'] }}" target="_blank">
                <img src="{{ $ad['image'] }}" />
            </a>
        </div>
        @endforeach
    @endif

<!-- 资讯顶部广告 -->
@elseif ($type == 2)

    @if(!$ads->isEmpty())
        <div class="unslider">
            <ul>
                @foreach($ads as $ad)
                  <li>
                    <a href="{{ $ad['link'] }}">
                        <img src="{{ $ad['image'] }}" width="100%" height="414">
                    </a>
                    @if ($ad['title'])
                        <a href="{{ $ad['link'] }}"><p class="title">{{ $ad['title'] }}</p></a>
                    @endif
                  </li>
                @endforeach
            </ul>
        </div>
    @endif

@elseif($type == 3 && isset($ads[$page-1]))

    <div class="ads_item">
        <dl class="user-box clearfix">
            <dt class="fl">
                <img class="round" src="{{ $ads[$page-1]['avatar'] }}" width="50">
            </dt>
            <dd class="fl ml20 body">
                <span class="tcolor">{{ $ads[$page-1]['name'] }}</span>
                <div class="font12 gcolor fr">{{ $ads[$page-1]['time'] }}</div>
            </dd>
        </dl>
        <a class="avatar_box" href="{{ $ads[$page-1]['link'] }}">
            <p class="mt0">{{ $ads[$page-1]['content'] }}</p>
            <div> <img src="{{ $ads[$page-1]['image'] }}" alt="image"> </div>
        </a>
        <p>
            <span class="tag">广告</span>
            {{-- <span class="options" onclick="options(this)">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-gengduo-copy"></use></svg>
            </span> --}}
        </p>
        <div class="feed_line"></div>
    </div>

@endif