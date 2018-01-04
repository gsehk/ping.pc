@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@if (!$comments->isEmpty())
    @foreach($comments as $comment)
        <dl class="message_one">
            <dt><img src="{{ getAvatar($comment['user'], 40) }}"></dt>
            <dd>
                <div class="one_title"><a href="/profile/{{$comment['user']['id']}}">{{$comment['user']['name']}}</a></div>
                <div class="one_date">{{ getTime($comment['created_at']) }}</div>

                <div class="top_comment">对帖子“<sapn>{{$comment['comment']['body']}}</sapn>”申请置顶，请及时处理。</div>

                <div class="comment_audit">
                    @if($comment['comment'] == null || $comment['post'] == null)
                        <a href="javascript:" class="red">该评论已被删除</a>
                    @elseif($comment['expires_at'] == null)
                        <a href="javascript:" class="green" data-args="type=1&post_id={{$comment['comment']['id']}}">同意置顶</a>
                        <a href="javascript:" class="green" data-args="type=-1&post_id={{$comment['comment']['id']}}">拒绝置顶</a>
                    @else
                        <a href="javascript:">已审核</a>
                    @endif
                </div>
            </dd>
        </dl>
    @endforeach

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + TS.TOKEN,
                'Accept': 'application/json'
            }
        });
        $('.comment_audit').on('click', 'a', function () {
            var _this = this;
            var data = urlToObject($(this).data('args'));
            var url = '';
            var type = 'PATCH';

            if (data.type == 1) {
                url = '/api/v2/plus-group/pinned/posts/' + data.post_id + '/accept';
            } else {
                url = '/api/v2/plus-group/pinned/posts/' + data.post_id + '/reject';
            }

            $.ajax({
                url: url,
                type: type,
                dataType: 'json',
                error: function(xml) {},
                success: function(res) {
                    if (data.type == 1){
                        $(_this).parent('.comment_audit').html('<a href="javascript:">已审核</a>');
                    } else {
                        $(_this).parent('.comment_audit').html('<a href="javascript:">已审核</a>');
                    }
                    TS.UNREAD.pinneds -= 1;
                    message.setUnreadMessage();
                }
            });
        });
    </script>

@endif
