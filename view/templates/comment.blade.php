@php
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$comments->isEmpty())
@foreach ($comments as $comment)
<div class="comment_item" id="comment_item_{{$comment['id']}}">
    <dl class="clearfix">
        <dt>
            <img src="{{$comment['user']['avatar']}}" width="50">
        </dt>
        <dd>
            <span class="reply_name">{{$comment['user']['name']}}</span>
            <div class="reply_tool feed_datas">
                <span class="reply_time">{{ getTime($comment['created_at']) }}</span>
                <span class="reply_action options">
                    <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                </span>
                <div class="options_div">
                    <ul>
                        <li>
                            <a href="javascript:;" onclick="comment.delComment({{$comment['id']}}, {{$comment['commentable_id']}});">
                                <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-shanchu-copy1"></use></svg>删除
                            </a>
                        </li>
                    </ul>
                    <img src="http://tss.io/zhiyicx/plus-component-pc/images/triangle.png" class="triangle">
                </div>
            </div>
            <div class="replay_body">{{$comment['body']}}</div>
        </dd>
    </dl>
</div>
@endforeach
@endif

