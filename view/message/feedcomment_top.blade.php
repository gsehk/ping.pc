@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@if (!$comments->isEmpty())
    @foreach($comments as $comment)
        <dl class="message-one">
            <dt><img src="{{$comment['user']['avatar']}}"></dt>
            <dd>
                <div class="one-title"><a href="/profile/{{$comment['user']['id']}}">{{$comment['user']['name']}}</a></div>
                <div class="one-date">{{ getTime($comment['created_at']) }}</div>

                <div class="top-comment">对你的动态进行了“<sapn>{{$comment['comment']['body']}}</sapn>”评论并申请置顶，请及时审核。</div>

                <div class="comment-audit">
                    @if($comment['expires_at'] == null)
                        <a href="javascript:" class="green" data-args="type=1&feed_id={{$comment['feed']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">同意置顶</a>
                        <a href="javascript:" class="green" data-args="type=-1&feed_id={{$comment['feed']['id']}}&comment_id={{$comment['comment']['id']}}&pinned_id={{$comment['id']}}">拒绝置顶</a>
                    @else
                        <a href="javascript:">同意置顶</a>
                    @endif
                </div>
            </dd>
        </dl>
    @endforeach

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + TOKEN,
                'Accept': 'application/json'
            }
        })
        $('.comment-audit').on('click', 'a', function () {
            var data = urlToObject($(this).data('args'));
            console.log(data);
            var url = '';
            var type = 'PATCH';

            if (data.type == 1) {
                url = '/api/v2/feeds/'+data.feed_id+'/comments/'+data.comment_id+'/pinneds/'+data.pinned_id;
            } else {
                url = '/api/v2/user/feed-comment-pinneds/'+data.pinned_id;
                type = 'DELETE';
            }

            $.ajax({
                url: url,
                type: type,
                dataType: 'json',
                error: function(xml) {},
                success: function(res, data, xml) {
                    console.log(res);
                    console.log(data);
                    console.log(xml);

                    if (xml.status == 201) {
                        noticebox(res.message, 1);
                    } else {
                        noticebox(res.message, 0);
                    }
                }
            });
        });
    </script>

@endif