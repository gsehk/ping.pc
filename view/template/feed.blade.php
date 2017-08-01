@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;
@endphp

<div class="feed-item" id="feed{{$feed->id}}">
    <div class="dy_c">
        <a class="avatar_box" href="{{ Route('pc:mine', $feed->user->id) }}">
        <img class="avatar" src="{{ $feed->user->avatar or $routes['resource'] . '/images/avatar.png' }}" />
        {{-- @if($feed->user->user_verified)
            <img class="vip_auth" src="{{ $routes['resource'] }}/images/vip_icon.svg">
        @endif --}}
        </a>
        <span class="dy_name fs-14">
        <a href="{{Route('pc:mine', $feed->user_id)}}">{{$feed->user->name}}</a></span>
        <a href="{{Route('pc:feedread', $feed->id)}}"><span class="dy_time fs-12">{{$feed->created_at}}</span></a>
    </div>
    <div class="cen_img">
        <p class="fs-14 cen_word">{!!$feed->feed_content!!}</p>
        @if($feed->images)
        <div style="position: relative; margin-bottom: 8px;" id="layer-photos-demo{{$feed->id}}">
        @if($feed->images->count() == 1)
          <img class="lazy img-responsive" style="min-width:100%;min-height:auto;" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}"/>
        @elseif($feed->images->count() == 2)
            <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="showImgBox">
                    <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 35vw;" class="showImgBox">
                    <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=277&h=273" />
              </div>
            </div>
        @elseif($feed->images->count() == 3)
            <div style="width: 100%; display: flex;">
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=184&h=180" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=184&h=180" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=184&h=180" />
              </div>
            </div>
        @elseif($feed->images->count() == 4)
            <div style="width: 100%; display: flex;">
            <div style="width: 50%">
              <div style="width: 100%;" class="showImgBox">
                    <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 100%;" class="showImgBox">
                    <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=277&h=273" />
              </div>
            </div>
              <div style="width: 50%">
              <div style="width: 100%;" class="showImgBox">
                    <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 100%;" class="showImgBox">
                    <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=277&h=273" />
              </div>
            </div>
            </div>
        @elseif($feed->images->count() == 5)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=370&h=366" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="showImgBox">
                  <img class="lazy perFeedImg"  data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=185&h=183" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="showImgBox">
                  <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=185&h=183" />
                </div>
              </div>
              <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=277&h=273" />
              </div>
              <div style="width: 35vw;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[4]['file']}}?w=277&h=273" />
              </div>
              </div>
            </div>
        @elseif($feed->images->count() == 6)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=370&h=366" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="showImgBox">
                  <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=185&h=183" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="showImgBox">
                  <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=185&h=183" />
                </div>
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=185&h=183" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[4]['file']}}?w=185&h=183" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[5]['file']}}?w=185&h=183" />
              </div>
            </div>
        @elseif($feed->images->count() == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 50%">
            <div style="width: 100%" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=277&h=273" />
            </div>
            <div style="width: 100%" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=277&h=273" />
            </div>
          </div>
          <div style="width: 50%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%; padding-bottom: 2px;" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 50%; padding-bottom: 2px;" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 100%;" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[4]['file']}}?w=277&h=273" />
            </div>
            <div style="width: 50%;" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[5]['file']}}?w=138&h=135" />
            </div>
            <div style="width: 50%;" class="showImgBox">
              <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[6]['file']}}?w=138&h=135" />
            </div>
          </div>
        </div>
        @elseif($feed->images->count() == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 50%;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=277&h=273" />
          </div>
          <div style="width: 50%;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[4]['file']}}?w=277&h=273" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[5]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[6]['file']}}?w=185&h=183" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[7]['file']}}?w=185&h=183" />
          </div>
        </div>
        @elseif($feed->images->count() == 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[0]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[1]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[2]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[3]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[4]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[5]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[6]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[7]['file']}}?w=185&h=181" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="lazy perFeedImg" data-original="{{$routes['storage']}}{{$feed->images[8]['file']}}?w=185&h=181" />
          </div>
        </div>
        @endif
        </div>
        @endif
        <div class="dy_comment">
            <span class="digg" id="digg{{$feed->id}}" rel="{{$feed->like_count}}">
                @if($feed->has_like)
                <a href="javascript:;" onclick="digg.delDigg({{$feed->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>{{$feed->like_count}}</font>
                </a>                
                @else 
                <a href="javascript:;" onclick="digg.addDigg({{$feed->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>{{$feed->like_count}}</font>
                </a>                
                @endif
            </span>
            <span class="com J-comment-show" data-args="box=#warp_box{{$feed->id}}&row_id={{$feed->id}}&canload=0">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$feed->id}}">{{$feed->feed_comment_count}}</font>
            </span>
            <span class="vie">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                {{$feed->feed_view_count}}
            </span>
            <span class="cen_right show_admin">
                <i class="icon iconfont icon-gengduo-copy"></i>
            </span>
            <div class="cen_more">
                <ul>
                    <li class="collect" id="collect{{$feed->id}}" rel="0"> 
                        @if($feed->has_collect)
                        <a href="javascript:;" onclick="collect.delCollect({{$feed->id}});" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collect.addCollect({{$feed->id}});"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if(!empty($TS['id']) && $feed->user_id != $TS['id'])
                    <li><a href="javascript:;" onclick="weibo.denounce(this);" feed_id="{{$feed->id}}" to_uid="{{$feed->user_id}}">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jubao-copy1"></use></svg>举报</a></li>
                    @endif
                    @if(!empty($TS['id']) && $feed->user_id == $TS['id'])
                    <li><a href="javascript:;" onclick="weibo.delFeed({{$feed->id}});"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除</a></li>
                    @endif
                    {{-- @if(!empty($TS['role']) && $TS['role']->role_id == 1)
                    <li><a href="javascript:;"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>置顶</a></li>
                    @endif --}}
                </ul>
                <img src="{{ $routes['resource'] }}/images/triangle.png" class="triangle" />
            </div>
        </div>
        <div class="comment_box" id="warp_box{{$feed->id}}" style="display: none;">
            <div class="dy_line">
                <img src="{{ $routes['resource'] }}/images/line.png" />
            </div>
            <div class="dy_comit" id="comment_box{{$feed->id}}">
            
            <div class="comment_box{{$feed->id}}">
            @if($feed->comments->count())
            @foreach($feed->comments as $cv)
            @php $user = createRequest('GET', '/api/v2/users/'.$cv->user_id); @endphp
            <p class="comment{{$cv->id}} comment_con">
                <span>{{$user->name}}：</span> {{$cv->body}}
                @if($cv->user_id != $TS['id'])
                    <a class="fs-14 J-reply-comment" data-args="to_uname={{$user->name}}&to_uid={{$cv->user_id}}&row_id={{$feed->id}}">回复</a>
                @endif
                @if($cv->user_id == $TS['id'])
                    <a class="fs-14 del_comment" onclick="comment.delComment({{$cv->id}}, {{$feed->id}})">删除</a>
                @endif
            </p>
            @endforeach
            @endif
            </div>
            @if($feed->comments->count() >= 3)
            <div class="comit_all fs-12"><a href="{{Route('pc:feedread', $feed->id)}}">查看全部评论</a></div>
            @endif
            
            </div>
        </div>
        <div class="f3"></div>
    </div>
</div>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo{{$feed->id}}'
  ,anim: 0 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数
  ,move: false
});
</script>
