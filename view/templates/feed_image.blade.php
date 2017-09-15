@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if (isset($image['paid']) && !$image['paid'])
    <div class="locked_image" @if (isset($type) && $type == 'one') style="position:relative" @endif>
	    <img src="{{ $routes['resource'] }}/images/pic_locked.png" class="feed_image_pay" data-node="{{ $image['paid_node'] }}" data-amount="{{ $image['amount'] }}" data-file="{{ $image['file'] }}" data-original="{{ getImageUrl($image, $width, $height) }}"/>
	    <svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>
    </div>
@else
	@if (isset($type) && $type == 'one')
	@php
	$size = explode('x', $image['size']);
	$style = $size[0] > $size[1] ? 'max-width:555px;height:auto' : 'max-height:400px;height:auto';
	$style = $size[0] < $size[1] ? 'max-height:400px;height:auto' : 'max-width:555px;height:auto';
    @endphp
    <img style="{{ $style }}" class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
    @else
    <img class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
	@endif
@endif