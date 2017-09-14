@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if (isset($image['paid']) && !$image['paid'])
    <div class="locked_image" >
    <img src="{{ $routes['resource'] }}/images/pic_locked.png" class="feed_image_pay"/>
    <svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true">
        <g>
            <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
        </g>
    </svg>
    </div>
@else
    <img class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
@endif