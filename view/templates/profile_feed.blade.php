@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp

@if(!$feeds->isEmpty())
@foreach($feeds as $key => $post)
<div class="feed_box">
<div class="feed_item feed_item_time" id="feed{{$post->id}}">

    <span class="feed_time">
        @if(date('Y-m-d') == date('Y-m-d', strtotime($post->created_at)))
            <span class="today">今天</span>
        @else
            <a href="{{ route('pc:feedread', $post->id) }}">
            <span class="profile_time">
                <sup style="font-size:90%">{{date('m', strtotime($post->created_at))}}</sup>
                <sub style="font-size:60%">{{date('d', strtotime($post->created_at))}} </sub>
            </span>
            </a>
        @endif
    </span>

    <div class="feed_body">
        <p class="feed_text">{!! formatContent($post->feed_content) !!}</p>

        @include('pcview::templates.feed_images')
    </div>

    <div class="feed_bottom">
        <div class="feed_datas">
            <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->like_count}}" status="{{(int) $post->has_like}}">
                @if($post->has_like)
                <a href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-likered"></use></svg> <font>{{$post->like_count}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$post->id}}, 'feeds', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-like"></use></svg> <font>{{$post->like_count}}</font>
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
                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
            </span>
            <div class="options_div">
                <ul>
                    <li id="J-collect{{$post->id}}" rel="0" status="{{(int) $post->has_collect}}">
                        @if($post->has_collect)
                        <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'feeds', 0);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-collect"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if(!empty($TS['id']) && $post->user_id == $TS['id'])
                    <li>
                        <a href="javascript:;" onclick="profile.pinneds({{$post->id}}, 'feeds');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" onclick="profile.delete({{$post->id}}, 'feeds');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-delete"></use></svg>删除
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
                <div class="comment_textarea">
                    <textarea class="comment-editor" id="J-editor{{$post->id}}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                    <div class="comment_post">
                        <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                        <a class="btn btn-primary fr" id="J-button{{$post->id}}" onclick="profile.addComment({{$post->id}}, 1, 'feeds')"> 评 论 </a>
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
                <div class="comit_all font12"><a href="{{Route('pc:feedread', $post->id)}}">查看全部评论</a></div>
                @endif
            </div>
        </div>
        <div class="feed_line"></div>
    </div>
</div>
</div>
<script type="text/javascript">
    var images = JSON.parse('{!!$post->images!!}'), data = new Array();
    if(images){
        for (var i in images) {
            var size = images[i].size.split('x');
            var img = {
                id: 'img' + i,
                img: TS.SITE_URL + '/api/v2/files/' + images[i].file + '?token=' + TS.TOKEN,
                tinyimg: TS.SITE_URL + '/api/v2/files/' + images[i].file + '?w=58&h=58&token=' + TS.TOKEN,
                width: size[0],
                height: size[1]
            };
            data.push(img);
        }
    }
    $('#feed_photos_{{$post->id}}').PicShow({
        data: data,
        bigWidth: {{ $conw or 635}},
        bigHeight: {{ $conh or 400}}
    });
</script>
@endforeach
@endif
