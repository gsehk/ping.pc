@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if(!$posts->isEmpty())
@foreach($posts as $key => $post)
<div class="feed_item" id="feed{{$post->id}}">
    <div class="feed_title">
        <a class="avatar_box" href="{{ route('pc:mine', $post->user->id) }}">
            <img class="avatar" src="{{ getAvatar($post->user, 50) }}" />
            @if($post->user->verified)
            <img class="role-icon" src="{{ $post->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </a>

        <a href="javascript:;">
            <span class="feed_uname font14">{{ $post->user->name }}</span>
        </a>
        <a href="{{ route('pc:grouppost', ['group_id' => $post->group_id, 'post_id' => $post->id]) }}" class="date">
            <span class="feed_time font12">{{ getTime($post->created_at) }}</span>
        </a>
    </div>

    {{-- <div class="post-title"><a href="{{ route('pc:grouppost', ['group_id' => $post->group_id, 'post_id' => $post->id]) }}">{{$post->title}}</a></div> --}}
    <div class="feed_body">
        <p class="feed_text">{!! formatContent($post->content) !!}</p>

        @include('pcview::templates.feed_images')
    </div>

    <div class="feed_bottom">
        <div class="feed_datas">
            <span class="digg" id="J-likes{{$post->id}}" rel="{{$post->diggs}}" status="{{(int) $post->has_like}}">
                @if($post->has_like)
                <a href="javascript:void(0)" onclick="liked.init({{$post->id}}, 'group', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
                    <font>{{$post->diggs}}</font>
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$post->id}}, 'group', 1)">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                    <font>{{$post->diggs}}</font>
                </a>
                @endif
            </span>
            <span class="comment J-comment-show">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg><font class="cs{{$post->id}}">{{$post->comments_count}}</font>
            </span>
            <span class="view">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>{{$post->views}}
            </span>
            <span class="options" onclick="options(this)">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </span>
            <div class="options_div">
                <div class="triangle"></div>
                <ul>
                    <li id="J-collect{{$post->id}}" rel="0" status="{{(int) $post->has_collection}}">
                        @if($post->has_collection)
                        <a class="act" href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 1);" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collected.init({{$post->id}}, 'group', 1);">
                          <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏
                        </a>
                        @endif
                    </li>
                    @if($post->user_id == $TS['id'])
                        <li>
                            <a href="javascript:;" onclick="post.delPost('{{$post->group_id}}', '{{$post->id}}');">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

         {{-- 评论 --}}
        @include('pcview::widgets.comments', ['id' => $post->id, 'group_id' => $post->group_id, 'comments_count' => $post->comments->count(), 'comments_type' => 'group', 'url' => Route('pc:grouppost', [$post->group_id, $post->id]), 'position' => 1, 'comments_data' => $post->comments])
       
        <div class="feed_line"></div>
    </div>
</div>
<script type="text/javascript">
    var images = JSON.parse('{!!$post->images!!}'), data = new Array();
    if(images){
        for (var i in images) {
            var size = images[i].size.split('x');
            var img = {
                id: 'img' + i,
                img: TS.SITE_URL + '/api/v2/files/' + images[i].id + '?token=' + TS.TOKEN,
                tinyimg: TS.SITE_URL + '/api/v2/files/' + images[i].id + '?w=58&h=58&token=' + TS.TOKEN,
                width: size[0],
                height: size[1]
            };
            data.push(img);
        }
    }
    $('#feed_photos_{{$post->id}}').actizPicShow({
        data: data,
        bigWidth: {{ $conw or 635}},
        bigHeight: {{ $conh or 400}}        
    });
</script>
@endforeach
@endif
