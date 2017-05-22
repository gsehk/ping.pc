
@if(isset($data))
@foreach($data as $key => $post)
<div class="cen_img cen_befor b_bg" @if($loop->first) style="margin-top:20px;" @endif>
    <span class="cen_beforColor_two"><span class="beforColor_span">{{date('m', strtotime($post['created_at']))}}</span>{{date('d', strtotime($post['created_at']))}}</span>
    <!-- <p class="fs-14 cen_word "></p> -->
    <div class="artic_div artic_list">
        <img src="{{$routes['storage']}}{{$post['storage']}}" class="img-responsive img1">
        <div class="img_title">
            <p class="i_title fs-20">{{$post['title']}}</p>
            <p class="i_subTiLe fs-12">{{$post['abstract'] or '这里是副标题'}}</p>
        </div>
    </div>                  
    <div class="dy_comment">
        @if($post['audit_status'] == 0)
        <span class="digg" id="collect{{$post['id']}}" rel="{{count($post['collection_count'])}}">
            <a href="javascript:;"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font>{{$post['collection_count']}}</font></a>
        </span>
        <span class="com J-comment-show" data-args="box=#warp_box{{$post['id']}}&row_id={{$post['id']}}&type=news&canload=0">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>{{$post['comment_count']}}
        </span>
        <span class="vie">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>{{$post['hits']}}
        </span>
        <!-- <span class="cen_right show_admin">
            <i class="icon iconfont icon-gengduo-copy"></i>
        </span>
        <div class="cen_more">
            <ul>
                <li><a href="#"><i class="icon iconfont icon-shoucang-copy"></i>收藏</a></li>
                <li><a href="#"><i class="icon iconfont icon-jubao-copy1"></i>举报</a></li>
                @if($post['author'] == $TS['id'])
                <li><a href="#"><i class="icon iconfont icon-shanchu-copy1"></i>删除</a></li>
                @endif
                @if($TS['role']->role_id == 1)
                <li><a href="#"><i class="icon iconfont icon-zhiding-copy-copy1"></i>置顶</a></li>
                @endif
            </ul>
        </div> -->
        @elseif($post['audit_status'] == 2) 
            <a href="{{route('pc:newsrelease', ['id'=>$post['id']])}}" class="p_continue">继续编辑</a>
        @endif
    </div>
    <div class="comment_box" id="warp_box{{$post['id']}}" style="display: none;">
        <div class="dy_line">
            <img src="{{ $routes['resource'] }}/images/line.png">
        </div>
        <div class="dy_comit" id="comment_box{{$post['id']}}">
                
            <div class="comment_box{{$post['id']}}">
            @if(!empty($post['comments']))
                @foreach($post['comments'] as $cv)
                @if($loop->index < 3)
                <p>
                    <span>{{$cv['user']['name']}}：</span> {{$cv['comment_content']}}
                    @if($cv['user_id'] != $TS['id'])
                        <a class="fs-14 J-reply-comment" data-args="to_uname={{$cv['user']['name']}}&to_uid={{$cv['user_id']}}&row_id={{$post['feed']['feed_id']}}">回复</a>
                    @endif
                </p>
                @endif
                @endforeach
            @endif
            </div>
            @if(count($post['comments']) >= 3)
            <div class="comit_all fs-12"><a href="/information/read/{{$post['id']}}">查看全部评论</a></div>
            @endif
        </div>
    </div>
    <div class="f3"></div>
</div>
@endforeach
@endif
