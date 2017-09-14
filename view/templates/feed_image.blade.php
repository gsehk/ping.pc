@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if (isset($image['paid']) && !$image['paid'])
    <div class="locked_image" >
	    <img src="{{ $routes['resource'] }}/images/pic_locked.png" class="feed_image_pay"/>
	    <svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>
    </div>
@else
    <img class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
@endif