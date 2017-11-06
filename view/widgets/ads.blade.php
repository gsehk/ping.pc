
{{-- 右侧广告位 --}}
@if ($type == 1)

    @if(!$ads->isEmpty())
        @foreach($ads as $ad)
        <div class="news_ad">
            <a href="{{ $ad['data']['link'] }}" target="_blank">
                <img src="{{ $ad['data']['image'] }}" />
            </a>
        </div>
        @endforeach
    @endif

{{-- 资讯顶部广告 --}}
@elseif ($type == 2)

    @if(!$ads->isEmpty())
        <div class="unslider">
            <ul>
                @foreach($ads as $ad)
                  <li>
                    <a href="{{ $ad['data']['link'] }}">
                        <img src="{{ $ad['data']['image'] }}" width="100%" height="414">
                    </a>
                    @if ($ad['title'])
                        <a href="{{ $ad['data']['link'] }}"><p class="title">{{ $ad['title'] }}</p></a>
                    @endif
                  </li>
                @endforeach
            </ul>
        </div>
    @endif

@elseif($type == 3 && isset($ads[$page-1]['data']))

    <div class="ads_item">
        <dl class="user-box clearfix">
            <dt class="fl">
                <img class="round" src="{{ $ads[$page-1]['data']['data']['avatar'] }}" width="50">
            </dt>
            <dd class="fl ml20 body">
                <span class="tcolor">{{ $ads[$page-1]['data']['name'] }}</span>
                <div class="font12 gcolor fr">{{ $ads[$page-1]['data']['time'] }}</div>
            </dd>
        </dl>
        <a class="avatar_box" href="{{ $ads[$page-1]['data']['link'] }}">
            <p class="mt0">{{ $ads[$page-1]['data']['content'] }}</p>
            <div> <img src="{{ $ads[$page-1]['data']['image'] }}" alt="image"> </div>
        </a>
        <p>
            <span class="tag">广告</span>
            {{-- <span class="options" onclick="options(this)">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-gengduo-copy"></use></svg>
            </span> --}}
        </p>
        <div class="feed_line"></div>
    </div>
@elseif($type == 4 && isset($ads[$page-1]['data']))
    <div class="news_item">
        <div class="news_img">
            <a href="{{ route('pc:newsread', ['news_id' => $item['id']]) }}">
                <img class="lazy" width="230" height="163" data-original="{{ $ads[$page-1]['data']['image'] }}"/>
            </a>
        </div>
        <div class="news_word">
            <div class="news_title"> {{ $ads[$page-1]['data']['title'] }} </div>
                <p>{{ $ads[$page-1]['data']['content'] }}</p>
                <div class="news_bm">
                   <span>{{ $ads[$page-1]['data']['name'] }}  ·  {{ $ads[$page-1]['data']['time'] }}</span>
                   <span class="tag ml10">广告</span>
                </div>
         </div>
    </div>
@endif