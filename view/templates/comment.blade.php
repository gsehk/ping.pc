
@if (!$comments->isEmpty())
@foreach ($comments as $comment)
<div class="comment_item" id="comment_item_{{$comment['id']}}">
    <dl class="clearfix">
        <dt>
            <img src="{{$comment['user']['avatar']}}" width="50">
        </dt>
        <dd>
            <span class="reply_name">{{$comment['user']['name']}}</span>
            <div class="reply_tool">
                <span class="reply_time">{{$comment['created_at']}}</span>
                <span class="reply_action"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg></span>
            </div>
            <div class="replay_body">{{$comment['body']}}</div>
        </dd>
    </dl>
</div>
@endforeach
@endif

