
{{-- 个人中心文章栏列表 --}}

@if(!$data->isEmpty())
@foreach($data as $key => $post)
<div class="feed_item" @if($loop->first) style="margin-top:20px;" @endif>

    <span class="feed_time">
        @if(date('Y-m-d') == date('Y-m-d', strtotime($post->created_at)))
            今天
        @else
            <span class="profile_time">
                <sup style="font-size:90%">{{ date('m', strtotime($post->created_at)) }}</sup>
                <sub style="font-size:60%">{{ date('d', strtotime($post->created_at)) }}</sub>
            </span>
        @endif
    </span>

    <div class="feed_body">
        <div class="article_box">
            <img data-original="{{$routes['storage']}}{{$post['storage']}}?w=584&h=400" class="lazy">
            <div class="article_desc">
                <p class="title"><a @if ($post->audit_status == 0) href="{{ route('pc:newsread', $post->id) }}" @endif>{{ $post['title'] }}</a></p>
                <p class="subject">{{ $post['subject'] or '' }}</p>
            </div>
        </div>
    </div>
    <div class="feed_bottom">
        @if ($post->audit_status == 0)
        <div class="feed_datas">
            <span class="collect" id="collect{{$post->id}}" rel="{{$post->collection_count}}">
                @if($post->has_collect)
                <a href="javascript:;" onclick="collect.delNews({{$post->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font> {{$post->collection_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="collect.news({{$post->id}})">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font> {{$post->collection_count}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}"> {{$post->comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg> {{$post->hits}}
            </span>
            {{-- <span class="options">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                    <li>
                        <a href="javascript:;" onclick="news.pinneds({{$post->id}});">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>申请置顶
                        </a>
                    </li>
                    @endif
                </ul>
                <img src="{{ asset('zhiyicx/plus-component-pc/images/triangle.png') }}" class="triangle" />
            </div> --}}
        </div>
        @endif
        <div class="comment_box" style="display: none;">
            <div class="comment_line">
                <img src="{{ asset('zhiyicx/plus-component-pc/images/line.png') }}" />
            </div>
            <div class="comment_body" id="comment_box{{$post->id}}">
                <div class="comment_textarea">
                    <textarea placeholder="" class="comment-editor" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a class="post_button a_link J-btn" onclick="comment.news(this)" to_uid="0" row_id="{{ $post->id }}">评论</a>
                    </div>
                </div>
                <div class="comment_ps" id="comment_wrap{{ $post->id }}">
                @if($post->comments->count())
                @foreach($post->comments as $cv)
                <p class="comment{{$cv->id}} comment_con">
                    <span>{{ $cv->user['name'] }}：</span> {{$cv->body}}
                    @if($cv->user_id != $TS['id'])
                        <a onclick="comment.initReply(this)" to_uname="{{ $cv->user['name'] }}" to_uid="{{$cv->user_id}}" row_id="{{$post->id}}">回复</a>
                    @endif
                    @if($cv->user_id == $TS['id'])
                        <a class="comment_del" onclick="comment.delNews({{$cv->id}}, {{$post->id}})">删除</a>
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
@endforeach
@endif
