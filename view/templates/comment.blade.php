@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if (!$comments->isEmpty())
@foreach ($comments as $comment)
<div class="comment_item" id="comment{{$comment['id']}}">
    <dl class="clearfix">
        <dt>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}">
                <img src="{{ getAvatar($comment['user'], 50) }}" width="50">
                @if($comment->user->verified)
                    <img class="role-icon" src="{{ $comment->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </dt>
        <dd>
            <a href="{{ route('pc:mine', $comment['user']['id']) }}"><span class="reply_name">{{$comment['user']['name']}}</span></a>
            <div class="reply_tool feed_datas">
                <span class="reply_time">{{ getTime($comment['created_at']) }}</span>
                @if ($comment['user']['id'] == $TS['id'])
                <span class="reply_action ml10 mt-3" onclick="options(this)">
                    <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                </span>
                <div class="options_div">
                    <div class="triangle"></div>
                    <ul>
                        @if(isset($top) ? $top : true)
                        <li>
                            <a href="javascript:;" class="mouse" onclick="comment.pinneds('{{$comment['commentable_type']}}', {{$comment['commentable_id']}}, {{$comment['id']}});">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-pinned2"></use></svg>申请置顶
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="javascript:;" class="mouse" onclick="comment.delete('{{$comment['commentable_type']}}', {{$comment['commentable_id']}}, {{$comment['id']}});">
                                <svg class="icon"><use xlink:href="#icon-delete"></use></svg>删除
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
                    回复<a href="{{ route('pc:mine', $user->id) }}">{{ '@'.$user->name }}</a>：
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

