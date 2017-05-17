
@if(isset($data))
@foreach($data as $key => $post)
<div class="feed-item">
    <div class="dy_c">
        <img src="{{$routes['storage']}}{{$post['user']['avatar'] or 7}}" />
        <span class="dy_name fs-14">{{$post['user']['name']}}</span>
        <span class="dy_time fs-12">{{$post['feed']['created_at']}}</span>
    </div>
    <div class="cen_img">
        <p class="fs-14 cen_word">{{$post['feed']['feed_content']}}</p>
        @if($post['feed']['storages'])
            @php $imgNum = count($post['feed']['storages']); @endphp
            @foreach($post['feed']['storages'] as $store)
            <img src="{{$routes['storage']}}{{$store['storage_id']}}" class="img-responsive" />
            @endforeach
        @endif
        <div class="dy_comment">
            <span class="digg">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                {{$post['tool']['feed_digg_count']}}
            </span>
            <span class="like J-comment-show" data-args="box=#warp_box{{$post['feed']['feed_id']}}&row_id={{$post['feed']['feed_id']}}&canload=0">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                {{$post['tool']['feed_comment_count']}}
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
                    @if($post['user_id'] == $TS['id'])
                    <li><a href="#"><i class="icon iconfont icon-shanchu-copy1"></i>删除</a></li>
                    @endif
                    @if($TS['role']->role_id == 1)
                    <li><a href="#"><i class="icon iconfont icon-zhiding-copy-copy1"></i>置顶</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="comment_box" id="warp_box{{$post['feed']['feed_id']}}" style="display: none;">
            <div class="dy_line">
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/line.png') }}" />
            </div>
            <div class="dy_comit" id="comment_box{{$post['feed']['feed_id']}}">
            
            <div class="comment_box{{$post['feed']['feed_id']}}">
                @if(!empty($post['comments']))
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
                <div class="comit_all fs-12"><a href="#">查看全部评论</a></div>
            </div>
        </div>
        <div class="f3"></div>
    </div>
</div>
@endforeach
@endif

