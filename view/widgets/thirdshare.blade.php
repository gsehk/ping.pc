@php
    $share_title = str_replace(array("\r", "\n"), array('', '\n'), addslashes($share_title));
@endphp

<a href="javascript:;" onclick="thirdShare(1, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到新浪微博">
    <svg class="icon third_share third_share_weibo" aria-hidden="true"><use xlink:href="#icon-weibo"></use></svg>
</a>
<a href="javascript:;" onclick="thirdShare(2, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到腾讯微博">
    <svg class="icon third_share third_share_qq" aria-hidden="true"><use xlink:href="#icon-qq"></use></svg>
</a>
<a href="javascript:;" onclick="thirdShare(3, '{{ $share_url }}', '{{ $share_title }}', '{{ $share_pic }}', this)" title="分享到朋友圈">
    <svg class="icon third_share third_share_weixin" aria-hidden="true"><use xlink:href="#icon-weixin"></use></svg>
</a>