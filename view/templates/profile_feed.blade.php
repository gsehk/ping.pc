@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@if(!$feeds->isEmpty())
@foreach($feeds as $key => $post)
<div class="feed_item">

    <span class="feed_time">
        @if(date('Y-m-d') == date('Y-m-d', strtotime($post->created_at)))
            今天
        @else
            <a href="{{ route('pc:feedread', ['feed' => $post->id]) }}">
            <span class="profile_time">
                <sup style="font-size:90%">{{date('m', strtotime($post->created_at))}}</sup>
                <sub style="font-size:60%">{{date('d', strtotime($post->created_at))}} </sub>
            </span>
            </a>
        @endif
    </span>

    <div class="feed_body">
        <p class="feed_text">{!! $post->feed_content !!}</p>

        @if($post->images)
        <div id="layer-photos-demo{{$post->id}}">
        @if($post->images->count() == 1)
          @php
              $size = explode('x', $post->images[0]['size']);
              $style = $size[0] < $size[1] ? 'max-height:400px;height:auto' : 'max-width:555px;height:auto';
          @endphp
            <img class="lazy" style="{{ $style }}" data-original="{{ getImageUrl($post->images[0], 555, 720) }}"/>
        @elseif($post->images->count() == 2)
            <div style="width: 100%; display: flex;">
                <div style="width: 35vw;" class="image_box">
                      <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[0], 277, 273) }}" />
                </div>
                <div style="width: 35vw;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 277, 273) }}" />
                </div>
            </div>
        @elseif($post->images->count() == 3)
            <div style="width: 100%; display: flex;">
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 184, 180) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[1], 184, 180) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[2], 184, 180) }}" />
                </div>
            </div>
        @elseif($post->images->count() == 4)
            <div style="width: 100%; display: flex;">
                <div style="width: 50%">
                  <div style="width: 100%;" class="image_box">
                        <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[0], 277, 273) }}" />
                  </div>
                  <div style="width: 100%;" class="image_box">
                        <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 277, 273) }}" />
                  </div>
                </div>
                <div style="width: 50%">
                  <div style="width: 100%;" class="image_box">
                        <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[2], 277, 273) }}" />
                  </div>
                  <div style="width: 100%;" class="image_box">
                        <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 277, 273) }}" />
                  </div>
                </div>
            </div>
        @elseif($post->images->count() == 5)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 66.6666%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 370, 366) }}" />
                </div>
                <div style="width: 33.3333%">
                    <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image"  data-original="{{ getImageUrl($post->images[1], 185, 183) }}" />
                    </div>
                    <div style="width: 100% padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[2], 185, 183) }}" />
                    </div>
                </div>
                <div style="width: 100%; display: flex;">
                    <div style="width: 35vw;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 277, 273) }}" />
                    </div>
                    <div style="width: 35vw;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[4], 277, 273) }}" />
                    </div>
                </div>
            </div>
        @elseif($post->images->count() == 6)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 66.6666%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 370, 366) }}" />
                </div>
                <div style="width: 33.3333%">
                    <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 185, 183) }}" />
                    </div>
                    <div style="width: 100% padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[2], 185, 183) }}" />
                    </div>
                </div>
                <div style="width: 33.3333%;" class="image_box">
                    <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                    <img class="lazy per_image" data-original="{{ getImageUrl($post->images[4], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                    <img class="lazy per_image" data-original="{{ getImageUrl($post->images[5], 185, 183) }}" />
                </div>
            </div>
        @elseif($post->images->count() == 7)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 50%">
                    <div style="width: 100%" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 277, 273) }}" />
                    </div>
                    <div style="width: 100%" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 277, 273) }}" />
                    </div>
                </div>
                <div style="width: 50%; display: flex; flex-wrap: wrap;">
                    <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[2], 138, 135) }}" />
                    </div>
                    <div style="width: 50%; padding-bottom: 2px;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 138, 135) }}" />
                    </div>
                    <div style="width: 100%;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[4], 277, 273) }}" />
                    </div>
                    <div style="width: 50%;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[5], 138, 135) }}" />
                    </div>
                    <div style="width: 50%;" class="image_box">
                      <img class="lazy per_image" data-original="{{ getImageUrl($post->images[6], 138, 135) }}" />
                    </div>
                </div>
            </div>
        @elseif($post->images->count() == 8)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[2], 185, 183) }}" />
                </div>
                <div style="width: 50%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 277, 273) }}" />
                </div>
                <div style="width: 50%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[4], 277, 273) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[5], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[6], 185, 183) }}" />
                </div>
                <div style="width: 33.3333%;" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[7], 185, 183) }}" />
                </div>
            </div>
        @elseif($post->images->count() == 9)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[0], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[1], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[2], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[3], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[4], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[5], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[6], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[7], 185, 181) }}" />
                </div>
                <div style="width: 33.3333%" class="image_box">
                  <img class="lazy per_image" data-original="{{ getImageUrl($post->images[8], 185, 181) }}" />
                </div>
            </div>
        @endif
        </div>
        @endif

    </div>

    <div class="feed_bottom">
        <div class="feed_datas">
            <span class="digg" id="digg{{$post->id}}" rel="{{$post->like_count}}">
                @if($post->has_like)
                <a href="javascript:;" onclick="digg.delDigg({{$post->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>{{$post->like_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="digg.addDigg({{$post->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>{{$post->like_count}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}">{{$post->feed_comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                {{$post->feed_view_count}}
            </span>
            <span class="options">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    <li id="collect{{$post->id}}" rel="0">
                        @if($post->has_collect)
                        <a href="javascript:;" onclick="collect.delCollect({{$post->id}});" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collect.addCollect({{$post->id}});">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>

                    {{-- @if(!empty($TS['id']) && $post->user_id != $TS['id'])
                    <li><a href="javascript:;"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>置顶</a></li>
                    @endif

                    @if(!empty($TS['id']) && $post->user_id != $TS['id'])
                    <li><a href="javascript:;" onclick="weibo.denounce(this);" feed_id="{{$post->id}}" to_uid="{{$post->user_id}}">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jubao-copy1"></use></svg>举报</a></li>
                    @endif --}}

                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                    <li><a href="javascript:;" onclick="weibo.delFeed({{$post->id}});"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除</a></li>
                    @endif
                </ul>
                <img src="{{ asset('zhiyicx/plus-component-pc/images/triangle.png') }}" class="triangle" />
            </div>
        </div>

        <div class="comment_box" style="display: none;">
            <div class="comment_line">
                <img src="{{ asset('zhiyicx/plus-component-pc/images/line.png') }}" />
            </div>
            <div class="comment_body" id="comment_box{{$post->id}}">
                <div class="comment_textarea" id="editor_box{{ $post->id }}">
                    <textarea placeholder="" class="comment-editor" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a href="javascript:;" class="post_button a_link J-comment-feed" to_uid="0" row_id="{{ $post->id }}">评论</a>
                    </div>
                </div>

                <div class="comment_ps" id="comment_ps{{ $post->id }}">
                @if($post->comments->count())
                @foreach($post->comments as $cv)
                <p class="comment{{$cv->id}} comment_con">
                    <span>{{ $cv->user['name'] }}：</span> {{$cv->body}}
                    @if($cv->user_id != $TS['id'])
                        <a class="J-reply-comment" data-args="to_uname={{ $cv->user['name'] }}&to_uid={{$cv->user_id}}&row_id={{$post->id}}">回复</a>
                    @endif
                    @if($cv->user_id == $TS['id'])
                        <a class="comment_del" onclick="comment.delComment({{$cv->id}}, {{$post->id}})">删除</a>
                    @endif
                </p>
                @endforeach
                @endif
                </div>
                @if($post->comments->count() >= 5)
                <div class="comit_all fs-12"><a href="{{Route('pc:feedread', $post->id)}}">查看全部评论</a></div>
                @endif

            </div>
        </div>
        <div class="feed_line"></div>
    </div>
</div>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo{{$post->id}}'
  ,anim: 0 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数
  ,move: false
});
</script>
@endforeach
@endif
