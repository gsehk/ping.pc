
@if(isset($data))
@foreach($data as $key => $post)
<div class="feed-item">
    <div class="dy_c">
        <a href="{{ route('pc:myFeed', ['user_id'=>$post['user']['id']]) }}"><img src="@if (!empty($post['user']['avatar'])) {{ $routes['storage']}}{{ $post['user']['avatar'] }} @else {{ $routes['resource'] }}/images/avatar.png @endif" /></a>
        <span class="dy_name fs-14"><a href="{{ route('pc:myFeed', ['user_id'=>$post['user']['id']]) }}">{{$post['user']['name']}}</a></span>
        <span class="dy_time fs-12">{{$post['feed']['created_at']}}</span>
    </div>
    <div class="cen_img">
        <p class="fs-14 cen_word">{{$post['feed']['feed_content']}}</p>
        @if($post['feed']['storages'])
        @php $imgNum = count($post['feed']['storages']); @endphp
        <div style="position: relative; margin-bottom: 8px;" id="layer-photos-demo{{$post['feed']['feed_id']}}">
        @if($imgNum == 1)
            <img src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" class="img-responsive"/>
        @elseif($imgNum == 2)
            <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="showImgBox">
                    <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
              </div>
              <div style="width: 35vw;" class="showImgBox">
                    <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
              </div>
            </div>
        @elseif($imgNum == 3)
            <div style="width: 100%; display: flex;">
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
              </div>
            </div>
        @elseif($imgNum == 4)
            <div style="width: 100%; display: flex;">
            <div style="width: 50%">
              <div style="width: 100%;" class="showImgBox">
                    <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
              </div>
              <div style="width: 100%;" class="showImgBox">
                    <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
              </div>
            </div>
              <div style="width: 50%">
              <div style="width: 100%;" class="showImgBox">
                    <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
              </div>
              <div style="width: 100%;" class="showImgBox">
                    <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
              </div>
            </div>
            </div>
        @elseif($imgNum == 5)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="showImgBox">
                  <img class="perFeedImg"  src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="showImgBox">
                  <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
                </div>
              </div>
              <div style="width: 100%; display: flex;">
              <div style="width: 35vw;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
              </div>
              <div style="width: 35vw;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][4]['storage_id']}}" />
              </div>
              </div>
            </div>
        @elseif($imgNum == 6)
            <div style="width: 100%; display: flex; flex-wrap: wrap;">
              <div style="width: 66.6666%" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%">
                <div style="width: 100%; padding-bottom: 2px;" class="showImgBox">
                  <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
                </div>
                <div style="width: 100% padding-bottom: 2px;" class="showImgBox">
                  <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
                </div>
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][4]['storage_id']}}" />
              </div>
              <div style="width: 33.3333%;" class="showImgBox">
                <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][5]['storage_id']}}" />
              </div>
            </div>
        @elseif($imgNum == 7)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 50%">
            <div style="width: 100%" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
            </div>
            <div style="width: 100%" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
            </div>
          </div>
          <div style="width: 50%; display: flex; flex-wrap: wrap;">
            <div style="width: 50%; padding-bottom: 2px;" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
            </div>
            <div style="width: 50%; padding-bottom: 2px;" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
            </div>
            <div style="width: 100%;" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][4]['storage_id']}}" />
            </div>
            <div style="width: 50%;" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][5]['storage_id']}}" />
            </div>
            <div style="width: 50%;" class="showImgBox">
              <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][6]['storage_id']}}" />
            </div>
          </div>
        </div>
        @elseif($imgNum == 8)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%; padding-bottom: 2px;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
          </div>
          <div style="width: 50%;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
          </div>
          <div style="width: 50%;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][4]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][5]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][6]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%;" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][7]['storage_id']}}" />
          </div>
        </div>
        @elseif($imgNum == 9)
        <div style="width: 100%; display: flex; flex-wrap: wrap;">
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][0]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][1]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][2]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][3]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][4]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][5]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][6]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][7]['storage_id']}}" />
          </div>
          <div style="width: 33.3333%" class="showImgBox">
            <img class="perFeedImg" src="{{$routes['storage']}}{{$post['feed']['storages'][8]['storage_id']}}" />
          </div>
        </div>
        @endif
        </div>
        @endif
        <div class="dy_comment">
            <span class="digg" id="digg{{$post['feed']['feed_id']}}" rel="{{$post['tool']['feed_digg_count']}}">
                @if($post['tool']['is_digg_feed'] <= 0)
                <a href="javascript:;" onclick="digg.addDigg({{$post['feed']['feed_id']}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>{{$post['tool']['feed_digg_count']}}</font>
                </a>
                @else 
                <a href="javascript:;" onclick="digg.delDigg({{$post['feed']['feed_id']}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font>{{$post['tool']['feed_digg_count']}}</font>
                </a>
                @endif
            </span>
            <span class="com J-comment-show" data-args="box=#warp_box{{$post['feed']['feed_id']}}&row_id={{$post['feed']['feed_id']}}&canload=0">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>{{$post['tool']['feed_comment_count']}}
            </span>
            <span class="vie">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                {{$post['tool']['feed_view_count']}}
            </span>
            <span class="cen_right show_admin">
                <i class="icon iconfont icon-gengduo-copy"></i>
            </span>
            <div class="cen_more">
                <ul>
                    <li><a href="#"><i class="icon iconfont icon-shoucang-copy"></i>收藏</a></li>
                    <li><a href="#"><i class="icon iconfont icon-jubao-copy1"></i>举报</a></li>
                    @if(!empty($TS['id']) && $post['user_id'] == $TS['id'])
                    <li><a href="#"><i class="icon iconfont icon-shanchu-copy1"></i>删除</a></li>
                    @endif
                    @if(!empty($TS['role']) && $TS['role']->role_id == 1)
                    <li><a href="#"><i class="icon iconfont icon-zhiding-copy-copy1"></i>置顶</a></li>
                    @endif
                </ul>
                <img src="{{ $routes['resource'] }}/images/triangle.png" class="triangle" />
            </div>
        </div>
        <div class="comment_box" id="warp_box{{$post['feed']['feed_id']}}" style="display: none;">
            <div class="dy_line">
                <img src="{{ $routes['resource'] }}/images/line.png" />
            </div>
            <div class="dy_comit" id="comment_box{{$post['feed']['feed_id']}}">
            
            <div class="comment_box{{$post['feed']['feed_id']}}">
            @if(count($post['comments']))
            @foreach($post['comments'] as $cv)
            <p>
                <span>{{$cv['user']['name']}}：</span> {{$cv['comment_content']}}
                @if($cv['user_id'] != $TS['id'])
                    <a class="fs-14 J-reply-comment" data-args="to_uname={{$cv['user']['name']}}&to_uid={{$cv['user_id']}}&row_id={{$post['feed']['feed_id']}}">回复</a>
                @endif
            </p>
            @endforeach
            @endif
            </div>
            @if(count($post['comments']) == 3)
            <div class="comit_all fs-12"><a href="/home/{{$post['feed']['feed_id']}}/feed">查看全部评论</a></div>
            @endif
            
            </div>
        </div>
        <div class="f3"></div>
    </div>
</div>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo{{$post["feed"]["feed_id"]}}'
  ,anim: 0 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
});
</script>
@endforeach
@endif
