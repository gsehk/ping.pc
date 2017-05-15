
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
            <span>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                {{$post['tool']['feed_digg_count']}}
            </span>
            <span>
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                {{$post['tool']['feed_comment_count']}}
            </span>
            <span>
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
        <div class="comment_box">
            <div class="dy_line">
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/line.png') }}" />
            </div>
            <div class="dy_comit">
                <p>
                    <span>Ellen：</span> 第一条评论
                    <a class="fs-14">回复</a>
                </p>
                <p>
                    <span>Nick </span>回复<span>Ellen：</span>
                    第二条评论
                </p>
                <p>
                    <span>Woody：</span> 回复第一条评论
                </p>
                <div class="comit_all fs-12">查看全部评论</div>
            </div>
        </div>
        <div class="f3"></div>
    </div>
</div>
@endforeach
@endif

