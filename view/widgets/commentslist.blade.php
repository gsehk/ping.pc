<div class="comment_box" style="display: none;">
    <div class="comment_line">
        <img src="{{ asset('zhiyicx/plus-component-pc/images/line.png') }}" />
    </div>
    <div class="comment_body" id="comment_box{{ $id }}">
        <div class="comment_textarea">
            <textarea class="comment-editor" id="J-editor{{ $id }}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
            <div class="comment_post">
                <span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
                <a class="btn btn-primary fr" id="J-button{{ $id }}" onclick="doComment()"> 评 论 </a>
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
                            回复{{ '@'.$user->name }}：
                        @endif

                        {{$cv->body}}
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
        @if($comments_count >= 5)
            <div class="comit_all font12"><a href="{{ $url }}">查看全部评论</a></div>
        @endif

    </div>
</div>