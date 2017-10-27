@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp

@if (!$comments->isEmpty())
@foreach ($comments as $comment)
<div class="comment_item" id="comment{{$comment['id']}}">
    <dl class="clearfix">
        <dt>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}">
                <img src="{{ $comment['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" width="50">
            </a>
        </dt>
        <dd>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}"><span class="reply_name">{{$comment['user']['name']}}</span></a>
            <div class="reply_tool feed_datas">
                <span class="reply_time">{{ getTime($comment['created_at']) }}</span>
                @if ($comment['user']['id'] == $TS['id'])
                <span class="reply_action ml10 mt-3" onclick="options(this)">
                    <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                </span>
                <div class="options_div">
                    <div class="triangle"></div>
                    <ul>
                        @if(isset($top) ? $top : true)
                        <li>
                            <a href="javascript:;" class="mouse" onclick="comment.pinneds('{{$comment['commentable_type']}}', {{$comment['commentable_id']}}, {{$comment['id']}});">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhiding-copy-copy1"></use></svg>申请置顶
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="javascript:;" class="mouse" onclick="comment.delete('{{$comment['commentable_type']}}', {{$comment['commentable_id']}}, {{$comment['id']}});">
                                <svg class="icon"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                            </a>
                        </li>
                    </ul>
                </div>
                @endif
                @if(isset($comment->top) && $comment->top == 1)
                    <span class="green fr">置顶</span>
                @endif
            </div>
            <div class="reply_body">
                @if ($comment->reply_user != 0)
                    @php
                        $user = getUserInfo($comment->reply_user);
                    @endphp
                    回复{{ '@'.$user->name }}：
                @endif
                
                {!! formatContent($comment['body']) !!}
                @if ($comment['user']['id'] != $TS['id'])
                    <a href="javascript:;" class="mouse" onclick="comment.reply('{{$comment['user']['id']}}', {{$comment['commentable_id']}}, '{{$comment['user']['name']}}')">回复</a>
                @endif
            </div>
        </dd>
    </dl>
</div>
@endforeach
@endif

