@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if (isset($image['paid']) && !$image['paid'])
    <div class="locked_image" @if (isset($type) && $type == 'one') style="position:relative" @endif data-node="{{ $image['paid_node'] }}" data-amount="{{ $image['amount'] }}" data-file="{{ $image['file'] }}" data-original="{{ getImageUrl($image, $width, $height) }}">
	    <img src="{{ $routes['resource'] }}/images/pic_locked.png" class="feed_image_pay"/>
	    <svg viewBox="0 0 18 18" class="lock" width="20%" height="20%" aria-hidden="true"><use xlink:href="#icon-suo"></use></svg>
    </div>
@else
	@if (isset($type) && $type == 'one')
	@php
    $size = explode('x', $image['size']);
    if ($size[0] > $size[1]) {
    	$w = $size[0] > 555 ? 555 : $size[0];
    	$h = number_format(($width / $size[0] * $size[1]), 2, '.', '');
    } 

    if ($size[0] < $size[1]) {
    	$h = $size[1] > 400 ? 400 : $size[1];
    	$w = number_format($height / $size[1] * $size[0], 2, '.', '');
    }

    if ($size[0] == $size[1]) {
    	$w = $h = $size[0] > 400 ? 400 : $size[0];
    }
    $style = 'width:' . $w . 'px;height:' . $h . 'px';
    @endphp
    <img style="{{ $style }}" class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
    @else
    <img class="lazy per_image" data-original="{{ getImageUrl($image, $width, $height) }}"/>
	@endif
@endif