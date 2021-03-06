@if($post->images)
    <div id="feed_photos_{{$post->id}}">
    <div class="feed_images">
    @if($post->images->count() == 1)
        @php
            // 单张图片默认展示宽高
            $conw = isset($conw) ? $conw : 555;
            $conh = isset($conh) ? $conh : 400;
        @endphp
        @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => $conw, 'height' => $conh, 'count' => 'one', 'curloc' => 0])
    @elseif($post->images->count() == 2)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273, 'curloc' => 0])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273, 'curloc' => 1])
            </div>
        </div>
    @elseif($post->images->count() == 3)
        <div style="width: 100%; display: flex;">
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 184, 'height' => 180, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 184, 'height' => 180, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 184, 'height' => 180, 'curloc' => 2])
            </div>
        </div>
    @elseif($post->images->count() == 4)
        <div style="width: 100%; display: flex;">
            <div style="width: 50%">
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273, 'curloc' => 0])
                </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273, 'curloc' => 1])
              </div>
            </div>
            <div style="width: 50%">
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 277, 'height' => 273, 'curloc' => 2])
              </div>
              <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273, 'curloc' => 3])
              </div>
            </div>
        </div>
    @elseif($post->images->count() == 5)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 370, 'height' => 366, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183, 'curloc' => 1])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183, 'curloc' => 2])
                </div>
            </div>
            <div style="width: 100%; display: flex;">
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273, 'curloc' => 3])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273, 'curloc' => 4])
                </div>
            </div>
        </div>
    @elseif($post->images->count() == 6)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 66.6666%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 370, 'height' => 366, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183, 'curloc' => 1])
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183, 'curloc' => 2])
                </div>
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 185, 'height' => 183, 'curloc' => 3])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 185, 'height' => 183, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 183, 'curloc' => 5])
            </div>
        </div>
    @elseif($post->images->count() == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%">
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 277, 'height' => 273, 'curloc' => 0])
                </div>
                <div style="width: 100%" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 277, 'height' => 273, 'curloc' => 1])
                </div>
            </div>
            <div style="width: 50%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 138, 'height' => 135, 'curloc' => 2])
                </div>
                <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 138, 'height' => 135, 'curloc' => 3])
                </div>
                <div style="width: 100%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273, 'curloc' => 4])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 138, 'height' => 135, 'curloc' => 5])
                </div>
                <div style="width: 50%;" class="image_box">
                    @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 138, 'height' => 135, 'curloc' => 6])
                </div>
            </div>
        </div>
    @elseif($post->images->count() == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 185, 'height' => 183, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 183, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 183, 'curloc' => 2])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 277, 'height' => 273, 'curloc' => 3])
            </div>
            <div style="width: 50%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 277, 'height' => 273, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 183, 'curloc' => 5])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 185, 'height' => 183, 'curloc' => 6])
            </div>
            <div style="width: 33.3333%;" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[7], 'width' => 185, 'height' => 183, 'curloc' => 7])
            </div>
        </div>
    @elseif($post->images->count() == 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[0], 'width' => 185, 'height' => 181, 'curloc' => 0])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[1], 'width' => 185, 'height' => 181, 'curloc' => 1])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[2], 'width' => 185, 'height' => 181, 'curloc' => 2])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[3], 'width' => 185, 'height' => 181, 'curloc' => 3])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[4], 'width' => 185, 'height' => 181, 'curloc' => 4])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[5], 'width' => 185, 'height' => 181, 'curloc' => 5])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[6], 'width' => 185, 'height' => 181, 'curloc' => 6])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[7], 'width' => 185, 'height' => 181, 'curloc' => 7])
            </div>
            <div style="width: 33.3333%" class="image_box">
                @include('pcview::templates.feed_image', ['image' => $post->images[8], 'width' => 185, 'height' => 181, 'curloc' => 8])
            </div>
        </div>
    @endif
    </div>
    </div>
@endif