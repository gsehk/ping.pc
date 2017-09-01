@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@foreach($news as $item)
<div class="news_item">
     <div class="news_img">
          <a href="{{ route('pc:newsread', ['news_id' => $item['id']]) }}">
               <img class="lazy" width="230" height="163" data-original=""/>
          </a>
     </div>
     <div class="news_word">
          <a href="{{ route('pc:newsread', ['news_id' => $item['id']]) }}">
               <div class="news_title"> {{ $item['title'] }} </div>
          </a>
          <p>{{ $item['subject'] }}</p>
          <div class="news_bm">
               <a href="javascript:;" class="cates_span">{{ $item['category']['name'] }}</a>
               <span>{{ $item['from'] }}  ·  {{ $item['hits'] }}浏览  ·  {{ getTime($item['created_at']) }}</span>
          </div>
     </div>
</div>
@endforeach