

<!-- 个人中心文章栏列表 -->


@if(isset($data))
@foreach($data as $key => $post)
<div class="cen_img cen_befor" @if($loop->first) style="margin-top:20px;" @endif>
    <span class="cen_beforColor">
    @if(date('Y-m-d') == date('Y-m-d', strtotime($post['created_at'])))
        今天
    @else
    <span style="font-size: 20px; writing-mode: horizontal-tb; "><sup style="font-size:90%">{{date('m', strtotime($post['feed']['created_at']))}}</sup> <sub style="font-size:60%">{{date('d', strtotime($post['feed']['created_at']))}} </sub></span>
    @endif
    </span>
    <!-- <p class="fs-14 cen_word "></p> -->
    <div class="artic_div artic_list">
        <img data-original="{{$routes['storage']}}{{$post['storage']}}" class="lazy img-responsive img1">
        <div class="img_title">
            <p class="i_title fs-20"><a href="/information/read/{{$post['id']}}">{{$post['title']}}</a></p>
            <p class="i_subTiLe fs-12">{{$post['subject'] or ''}}</p>
        </div>
    </div>                  
    <div class="dy_comment">
        @if($post['audit_status'] == 0)
        <span class="digg" id="collect{{$post['id']}}" rel="{{$post['collection_count']}}"> 
            @if($post['is_collection_news'])
            <a href="javascript:;" onclick="collect.delNewsCollect({{$post['id']}});" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cos">{{$post['collection_count']}}</font>
            </a>
            @else
            <a href="javascript:;" onclick="collect.addNewsCollect({{$post['id']}});"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cos">{{$post['collection_count']}}</font>
            </a>
            @endif
        </span>
        <span class="com J-comment-show" data-args="box=#warp_box{{$post['id']}}&row_id={{$post['id']}}&type=news&canload=0">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post['id']}}">{{$post['comment_count']}}</font>
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
                <p class="comment{{$cv['id']}} comment_con">
                    <span>{{$cv['user']['name']}}：</span> {{$cv['comment_content']}}
                    @if($cv['user_id'] != $TS['id'])
                        <a class="fs-14 J-reply-comment" data-args="to_uname={{$cv['user']['name']}}&to_uid={{$cv['user_id']}}&row_id={{$post['id']}}">回复</a>
                    @endif
                    @if($cv['user_id'] == $TS['id'])
                        <a class="fs-14 del_comment" onclick="comment.delNewsComment({{$cv['id']}}, {{$post['id']}})">删除</a>
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
