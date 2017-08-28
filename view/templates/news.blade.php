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
               <div class="infW_title"> {{ $item['title'] }} </div>
          </a>
          <p>{{ $item['subject'] }}</p>
          <div class="news_bm">
               <span class="news_time">{{ getTime($item['created_at']) }}</span>
               <span class="news_comment"> {{ $item['comment_count'] }} 评论<span>|</span> {{ $item['collection_count'] }} 收藏</span>
          </div>
     </div>
</div>
@endforeach