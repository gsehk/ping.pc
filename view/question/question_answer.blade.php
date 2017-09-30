@if(!$answers->isEmpty())
    @foreach ($answers as $answer)
        <div class="list-item" id="answer{{$answer->id}}">
            <div class="list-item-header">
                <span class="userlink authorinfo-avatarwrapper">
                    @if($answer->anonymity == 1)
                        <img class="avatar avatar--round" width="44" height="44" src="{{ asset('zhiyicx/plus-component-pc/images/ico_anonymity_60.png') }}" alt="">
                    @else
                        <img class="avatar avatar--round" width="44" height="44" src="{{$answer->user->avatar  or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" alt="">
                    @endif
                </span>
                <div class="authorinfo-content">
                    <div class="authorinfo-head">
                        <span class="userlink authorinfo-name">{{ $answer->anonymity == 1 ? '匿名用户' : $answer->user->name }}</span>
                        @if(isset($answer->invitation) && $answer->invitation == 1)
                            <span class="blue-tag">邀请回答</span>
                        @endif
                        @if($answer->adoption == 1)
                            <span class="green-tag">已采纳</span>
                        @endif
                    </div>
                    <div class="authorinfo-time">
                        <span>{{ $answer->created_at }}</span>
                    </div>
                </div>
            </div>
            <div class="list-item-content">
                <div class="content-inner">
                    {{--@if(isset($answer->invitation) && $answer->invitation == 1)--}}
                        {{--{{ $answer->body }}--}}
                    {{--@endif--}}
                    {{$answer->body = Parsedown::instance()->setMarkupEscaped(true)->text($answer->body)}}
                    @if(isset($answer->invitation) && $answer->invitation == 1 && !$answer->cloud)
                        <span class="answer-body fuzzy">{!! str_limit(strip_tags($answer->body), 250, "...") !!}</span>
                    @else
                        <span class="hide show-answer-body">{!! $answer->body !!}</span>
                        <span class="answer-body">{!! str_limit(strip_tags($answer->body), 250, "...") !!}</span>
                        <button class="button button-plain button-more"><a href="{{ route('pc:answeread', $answer->id) }}">查看详情</a></button>
                    @endif
                </div>

                <div class="answer-footer">
                    <div class="answer-footer-inner">
                        <a href="{{ route('pc:answeread', $answer->id) }}" class="button button-plain">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                            {{ $answer->comments_count }} 评论
                        </a>
                        <a href="{{ route('pc:answeread', $answer->id) }}" class="button button-plain">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                            {{ $answer->likes_count }} 分享
                        </a>
                        <a href="javascript:;" class="button button-plain" id="J-likes{{$answer->id}}" onclick="liked.init({{$answer->id}}, 'question', 1);" status="{{(int) (isset($TS) && $answer->liked)}}" rel="{{ $answer['likes_count'] }}">
                            @if(isset($TS) && $answer->liked)
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
                            @else
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                            @endif
                            <font>{{ $answer->likes_count }}</font> 点赞
                        </a>

                        <a href="javascript:;" class="button button-plain options" type="button" aria-haspopup="true" aria-expanded="false" onclick="options(this)">
                            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                        </a>
                        <div class="options_div">
                            <div class="triangle"></div>
                            <ul>
                                @if(isset($TS) && $answer->question->user_id == $TS['id'])
                                    <li>
                                        @if($answer->adoption == 1)
                                            <a href="javascript:;">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>已采纳
                                            </a>
                                        @else
                                            <a href="javascript:;" onclick="QA.adoptions('{{$answer['question_id']}}', '{{$answer['id']}}')">
                                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>采纳
                                            </a>
                                        @endif
                                    </li>
                                @endif
                                <li>
                                    <a href="javascript:;" onclick="collected.init({{$answer->id}}, 'question', 0);" id="J-collect{{$answer->id}}" status="{{(int) (isset($TS) && $answer->collected)}}">
                                        @if(isset($TS) && $answer['collected'] == 1)
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                                            <span class="collect">已收藏</span>
                                        @else
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                                            <span class="collect">收藏</span>
                                        @endif
                                    </a>
                                </li>
                                @if(isset($TS) && $answer->user_id == $TS['id'])
                                    <li>
                                        <a href="javascript:;" onclick="QA.delAnswer({{$answer->question_id}}, {{$answer->id}})">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                                        </a>
                                    </li>
                                {{--@else--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;" onclick="">--}}
                                            {{--<svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>举报--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                @endif
                            </ul>
                        </div>

                        @if(isset($answer->invitation) && $answer->invitation == 1)
                            <div class="look-answer">
                                <span class="look-user">{{ $answer['onlookers_count'] }}人正在围观</span>
                                @if(!$answer->cloud)
                                    <button class="button button-blue button-primary look-cloud" onclick="QA.look({{ $answer->id }}, 0.1, {{ $answer->question_id }})" type="button">围观</button>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif