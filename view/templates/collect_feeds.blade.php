@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if(!$feeds->isEmpty())
@foreach($feeds as $key => $post)
<div class="feed_item" @if($loop->first) style="margin-top:20px;" @endif>

    <div class="feed_title">
        <a href="{{ route('pc:mine', $post->user->id) }}">
            <img class="round" src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" width="50" />
            @if($post->user->user_verified)
            <img class="vip_auth" src="{{ asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </a>
        <span class="uname tcolor">{{ $post->user->name }}</span>
        <span class="collect_time">{{ getTime($post->created_at) }}</span>
    </div>

    <div class="feed_body">
        <a href="{{ route('pc:feedread', $post->id) }}"><p class="feed_text tcolor">{!! $post->feed_content !!}</p></a>

        @if($post->images)
        <div style="" id="layer-photos-demo{{$post->id}}">
        @if($post->images->count() == 1)
          <img class="lazy" style="max-width:100%;min-height:auto;" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}"/>
        @elseif($post->images->count() == 2)
            <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="image_box">
                    <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 35vw;" class="image_box">
                    <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=277&h=273" />
              </div>
            </div>
        @elseif($post->images->count() == 3)
            <div style="width: 100%; display: flex;">
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=184&h=180" />
              </div>
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=184&h=180" />
              </div>
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=184&h=180" />
              </div>
            </div>
        @elseif($post->images->count() == 4)
            <div style="width: 100%; display: flex;">
            <div style="width: 50%">
              <div style="width: 100%;" class="image_box">
                    <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 100%;" class="image_box">
                    <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=277&h=273" />
              </div>
            </div>
              <div style="width: 50%">
              <div style="width: 100%;" class="image_box">
                    <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 100%;" class="image_box">
                    <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=277&h=273" />
              </div>
            </div>
            </div>
        @elseif($post->images->count() == 5)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=370&h=366" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image"  data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=185&h=183" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=185&h=183" />
                </div>
              </div>
              <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 35vw;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[4]['file']}}?w=277&h=273" />
              </div>
              </div>
            </div>
        @elseif($post->images->count() == 6)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=370&h=366" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=185&h=183" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="image_box">
                  <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=185&h=183" />
                </div>
              </div>
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=185&h=183" />
              </div>
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[4]['file']}}?w=185&h=183" />
              </div>
              <div style="width: 33.3333%;" class="image_box">
                <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[5]['file']}}?w=185&h=183" />
              </div>
            </div>
        @elseif($post->images->count() == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 50%">
            <div style="width: 100%" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=277&h=273" />
            </div>
            <div style="width: 100%" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=277&h=273" />
            </div>
          </div>
          <div style="width: 50%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%; padding-bottom: 2px;" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 50%; padding-bottom: 2px;" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 100%;" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[4]['file']}}?w=277&h=273" />
            </div>
            <div style="width: 50%;" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[5]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 50%;" class="image_box">
              <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[6]['file']}}?w=138&h=135" />
            </div>
          </div>
        </div>
        @elseif($post->images->count() == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 50%;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=277&h=273" />
          </div>
          <div style="width: 50%;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[4]['file']}}?w=277&h=273" />
          </div>
          <div style="width: 33.3333%;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[5]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[6]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%;" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[7]['file']}}?w=185&h=183" />
          </div>
        </div>
        @elseif($post->images->count() == 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[0]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[1]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[2]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[3]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[4]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[5]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[6]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[7]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="image_box">
            <img class="lazy per_image" data-original="{{$routes['storage']}}{{$post->images[8]['file']}}?w=185&h=181" />
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
                      <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font> {{$post->like_count}}</font>
                  </a>
                @else
                  <a href="javascript:;" onclick="digg.addDigg({{$post->id}})">
                      <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font> {{$post->like_count}}</font>
                  </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}"> {{$post->feed_comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg> {{$post->feed_view_count}}
            </span>
            <span class="options">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    <li id="collect{{$post->id}}" rel="0">
                        @if($post->has_collect)
                        <a href="javascript:;" onclick="collect.delWeibo({{$post->id}});" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collect.weibo({{$post->id}});">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                      <li>
                          <a href="javascript:;" onclick="weibo.delete({{$post->id}});">
                              <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                          </a>
                      </li>
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
                        <span class="dy_cs">可输入<span class="nums mcolor">255</span>字</span>
                        <a class="post_button a_link J-btn" onclick="comment.weibo(this);" to_uid="0" row_id="{{ $post->id }}">评论</a>
                    </div>
                </div>

                <div class="comment_ps" id="comment_ps{{ $post->id }}">
                @if($post->comments->count())
                  @foreach($post->comments as $cv)
                  <p class="comment{{$cv->id}} comment_con">
                      <span>{{ $cv->user['name'] }}：</span> {{$cv->body}}
                        @if($cv->user_id != $TS['id'])
                            <a
                              onclick="comment.initReply(this)"
                              to_uname="{{ $cv->user['name'] }}"
                              to_uid="{{$cv->user_id}}"
                              row_id="{{$post->id}}"
                            >回复</a>
                        @endif
                      @if($cv->user_id == $TS['id'])
                          <a class="comment_del" onclick="comment.delWeibo({{$cv->id}}, {{$post->id}})">删除</a>
                      @endif
                  </p>
                  @endforeach
                @endif
                </div>
                @if($post->comments->count() >= 5)
                  <div class="comit_all fs-12"><a href="{{ Route('pc:feedread', $post->id) }}">查看全部评论</a></div>
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
