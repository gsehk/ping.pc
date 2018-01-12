@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
{{-- 个人中心文章栏列表 --}}

@if(!$data->isEmpty())
@foreach($data as $key => $post)
<div class="news_item @if ($loop->iteration == 1)mt30 @endif">

    <div class="news_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
            <img class="avatar" src="{{ getAvatar($post->user, 50) }}" width="50" />
            @if($post->user->verified)
            <img class="role-icon" src="{{ $post->user->verified->icon or asset('assets/pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="uname font14">{{ $post->user->name }}</span>
        </a>

        <a class="date" href="{{ route('pc:newsread', $post->id) }}">
            <span class="font12">{{ getTime($post->created_at) }}</span>
            <span class="font12 hide">查看详情</span>
        </a>
    </div>

    <div class="news_body">
        <div class="article_box relative">
            <img data-original="{{$routes['storage']}}{{$post['storage']}}?w=584&h=400" class="lazy img">
            <div class="article_desc">
                <p class="title"><a @if ($post->audit_status == 0) href="{{ route('pc:newsread', $post->id) }}" @endif>{{ $post['title'] }}</a></p>
                <p class="subject">{{ $post['subject'] or '' }}</p>
            </div>
        </div>
    </div>
    <div class="news_bottom mt20">
        @if ($post->audit_status == 0)
        <div class="feed_datas relative">
            <span class="collect" id="J-collect{{$post->id}}" rel="{{$post->collection_count}}" status="{{(int) $post->has_collect}}">
                @if($post->has_collect)
                <a href="javascript:;" onclick="collected.init({{$post->id}}, 'news', 1);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font>{{$post->collection_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="collected.init({{$post->id}}, 'news', 1);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>
                    <font>{{$post->collection_count}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                <font class="cs{{$post->id}}">{{$post->comment_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg> {{$post->hits}}
            </span>
            <span class="options" onclick="options(this)">
                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
            </span>
            <div class="options_div">
                <div class="triangle"></div>
                <ul>
                    @if($post->user_id == $TS['id'])
                    <li>
                        <a href="javascript:;" onclick="news.pinneds({{$post->id}}, 'news');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
        <div class="comment_box" style="display: none;">
            <div class="comment_line">
                <img src="{{ asset('assets/pc/images/line.png') }}" />
            </div>
            <div class="comment_body" id="comment_box{{$post->id}}">
                <div class="comment_textarea">
                    <textarea class="comment-editor" id="J-editor{{$post->id}}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a class="btn btn-primary fr" id="J-button{{$post->id}}" onclick="news.addComment({{$post->id}}, 1)"> 评 论 </a>
                    </div>
                </div>
                <div id="J-commentbox{{ $post->id }}">
                    @if($post->comments->count())
                        @foreach($post->comments as $cv)
                            <p class="comment_con" id="comment{{$cv->id}}">
                                <span class="tcolor">{{ $cv->user['name'] }}：</span> {{$cv->body}}
                                @if(isset($cv->pinned) && $cv->pinned == 1)
                                    <span class="green">置顶</span>
                                @endif
                                @if($cv->user_id != $TS['id'])
                                    <a onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}')">回复</a>
                                @else
                                    <a class="comment_del" onclick="comment.pinneds('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">申请置顶</a>
                                    <a class="comment_del" onclick="comment.delete('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
                                @endif
                            </p>
                        @endforeach
                    @endif
                </div>
                @if($post->comments->count() >= 5)
                <div class="comit_all font12"><a href="{{Route('pc:newsread', $post->id)}}">查看全部评论</a></div>
                @endif

            </div>
        </div>
        <div class="feed_line"></div>
    </div>
</div>
@endforeach
@endif