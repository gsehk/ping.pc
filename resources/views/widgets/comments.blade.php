@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getUserInfo;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatContent;
@endphp
@if($position == 1)
    <div class="comment_box" style="display: none;">
        <div class="comment_line">
            <div class="tr2"></div>
            {{-- <img src="{{ asset('assets/pc/images/line.png') }}" /> --}}
        </div>
        <div class="comment_body" id="comment_box{{ $id }}">
            <div class="comment_textarea">
                <textarea class="comment-editor" id="J-editor{{ $id }}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                <div class="comment_post">
                    <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                    <a class="btn btn-primary fr" id="J-button{{ $id }}" onclick="doComment('{{ $id }}', '{{ $position or 0 }}', '{{ $comments_type }}', '{{ $group_id or 0 }}')"> 评 论 </a>
                </div>
            </div>
            <div id="J-commentbox{{ $id }}">
                @if($comments_count)
                    @foreach($comments_data as $cv)
                        <p class="comment_con" id="comment{{$cv->id}}">
                            <span class="tcolor">{{ $cv->user['name'] }}：</span>
                            @if ($cv->reply_user != 0)
                                @php
                                    $user = getUserInfo($cv->reply_user);
                                @endphp
                                回复<a href="{{ route('pc:mine', $user->id) }}">{{ '@'.$user->name }}</a>：
                            @endif

                            {!! formatContent($cv->body) !!}

                            @if(isset($cv->pinned) && $cv->pinned == 1)
                                <span class="mouse green">置顶</span>
                            @endif

                            @if (
                                    ($TS['id'] == $cv->user_id) ||
                                    (isset($group->joined) && in_array($group->joined->role, ['administrator', 'founder']))
                                )
                                @if(isset($top) && $top == 1 && $TS['id'] == $cv->user_id)
                                    <a class="mouse comment_del" onclick="comment.pinneds('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">申请置顶</a>
                                @endif
                                <a class="mouse comment_del" onclick="comment.delete('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
                            @else
                                <a class="mouse" onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}')">回复</a>
                                @if (isset($group->joined) && $group->joined->role == 'member')
                                    <a class="mouse" onclick="post.reportComment('{{$cv['id']}}');">举报</a>
                                @endif
                            @endif
                        </p>
                    @endforeach
                @endif
            </div>
            @if($comments_count >= 5)
                <div class="comit_all font12"><a href="{{ $url }}">查看全部评论</a></div>
            @endif

        </div>
    </div>
@else
    <div class="detail_comment @if(isset($add_class)) {{ $add_class }} @endif">
        @if($comments_type == 'question')
            <span id="answer-button" class="answer-button">
                <img src="{{asset('assets/pc/images/arrow_news_up.png')}}" alt="">
            </span>
        @endif
        <div class="comment_title"><span class="comment_count cs{{ $id }}">{{ $comments_count }} </span>人评论</div>
        <div class="comment_box">
            <textarea class="comment_editor" id="J-editor{{ $id }}" placeholder="说点什么吧" onkeyup="checkNums(this, 255, 'nums');"></textarea>
            <div class="comment_tool">
                <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                <button class="btn btn-primary" id="J-button{{ $id }}" onclick="doComment('{{ $id }}', '{{ $position or 0 }}', '{{ $comments_type }}', '{{ $group_id or 0 }}')"> 评 论 </button>
            </div>
        </div>
        <div class="comment_list J-commentbox" id="J-commentbox{{ $id }}"></div>
    </div>
@endif

<script>
    var params = {};
    var position = '{{ $position or 0 }}';
    if (position == 0) {
        setTimeout(function() {
            var comments_type = '{{ $comments_type }}';
            var loading = '{{ $loading or ''}}';
            var id = '{{ $id }}';
            var url = '';
            var group_id = '{{ $group_id or 0 }}';
            if (group_id) {
                params.group_id = group_id;
            }

            if (comments_type == 'answer') {

                url = '/question/answer/' + id + '/comments';

            } else if (comments_type == 'question') {

                return ;
                //url = '/question/' + id + '/comments';

            } else if (comments_type == 'feed') {

                url = '/feeds/' + id + '/comments'

            } else if (comments_type == 'group') {

                url = '/group/' + id + '/comments' ;

            } else if (comments_type == 'news') {

                url = '/news/' + id + '/comments';
            }

            scroll.init({
                container: '.J-commentbox',
                loading: loading,
                url: url,
                params: params,
                canload: true
            });
        }, 200);
    }

    function doComment(id, position, comments_type, group_id) {
        var url = '';
        if (comments_type == 'answer') {

            url = '/api/v2/question-answers/' + id + '/comments';

        } else if (comments_type == 'question') {

            url = '/api/v2/questions/' + id + '/comments';

        } else if (comments_type == 'feed') {

            url = '/api/v2/feeds/' + id + '/comments'

        } else if (comments_type == 'group') {

            url = '/api/v2/plus-group/group-posts/' + id + '/comments'

        } else if (comments_type == 'news') {

            url = '/api/v2/news/' + id + '/comments';
        }

        comment.support.row_id = id;
        comment.support.position = position == '1' ? 1 : 0;
        comment.support.editor = $('#J-editor' + id);
        comment.support.button = $('#J-button' + id);
        comment.support.top = {{ isset($top) ? $top : 0 }};


        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs' + id).text(parseInt($('.cs' + id).text()) + 1);
        });
    }

</script>