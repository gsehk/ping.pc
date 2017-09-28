@if(!$answers->isEmpty())
    @foreach ($answers as $answer)
        <div class="list-item">
            <div class="list-item-header">
                <span class="userlink authorinfo-avatarwrapper">
                    <img class="avatar avatar--round" width="44" height="44" src="{{$answer->user->avatar  or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" alt="">
                </span>
                <div class="authorinfo-content">
                    <div class="authorinfo-head">
                        <span class="userlink authorinfo-name">{{ $answer->user->name }}</span>
                    </div>
                    <div class="authorinfo-time">
                        <span>{{ $answer->created_at }}</span>
                    </div>
                </div>
            </div>
            <div class="list-item-content">
                <div class="content-inner">
                    <span class="hide">{!! $answer->body = Parsedown::instance()->setMarkupEscaped(true)->text($answer->body) !!}</span>
                    <span class="answer-body">{!! str_limit(strip_tags($answer->body), 250, "...") !!}</span>
                    <button class="button button-plain button-more"><a href="{{ route('pc:answeread', $answer->id) }}">查看详情</a></button>
                </div>

                <div class="answer-footer">
                    <div class="answer-footer-inner">
                        <button class="button button-plain">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                            {{ $answer->comments_count }} 评论
                        </button>
                        <button class="button button-plain">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                            {{ $answer->likes_count }} 分享
                        </button>
                        <button class="button button-plain" id="J-likes{{$answer->id}}" onclick="liked.init({{$answer->id}}, 'question', 1);" status="{{(int) $answer->liked}}" rel="{{ $answer['likes_count'] }}">
                            @if($answer->liked == 1)
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
                            @else
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                            @endif
                            <font>{{ $answer->likes_count }}</font> 点赞
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif