
              <div class="comment_box{{$post->id}}">
              @if($post->comments->count())
              @foreach($post->comments as $cv)
              <p class="comment{{$cv->id}} comment_con">
                  <span>{{ $cv->user['name'] }}：</span> {{$cv->body}}
                  @if($cv->user_id != $TS['id'])
                      <a class="fs-14 J-reply-comment" data-args="to_uname={{ $cv->user['name'] }}&to_uid={{$cv->user_id}}&row_id={{$post->id}}">回复</a>
                  @endif
                  @if($cv->user_id == $TS['id'])
                      <a class="fs-14 del_comment" onclick="comment.delComment({{$cv->id}}, {{$post->id}})">删除</a>
                  @endif
              </p>
              @endforeach
              @endif
              </div>

              @if($post->comments->count() >= 3)
              <div class="comit_all fs-12"><a href="{{Route('pc:feedread', $post->id)}}">查看全部评论</a></div>
              @endif
            