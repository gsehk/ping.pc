@section('title') 问答详情 @endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/q_d.css') }}" />
@endsection

@section('content')
<div class="questionpage">
    <!-- question-header -->
    <div class="questionheader">
        <div class="questionheader-content">
            <div class="questionheader-main">
                <span class="questionheader-price">￥{{ $question->amount/100 }}</span>
                <div class="questionheader-tags">
                    <div class="questionheader-topics">
                        @if (!$question->topics->isEmpty())
                            @foreach ($question->topics as $topic)
                                <div class="tag questiontopic">
                                    <span class="tag-content">
                                        <a class="topiclink" href="#">{{ $topic->name }}</a>
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <h1 class="questionheader-title">{{ $question->subject }}</h1>
                <div class="questionheader-detail">
                    <!-- js增删  .questionrichtext--collapsed 改变content字数 -->
                    <div class="questionrichtext questionrichtext--expandable questionrichtext--collapsed">
                        <div>
                            <span class="richtext" itemprop="text">{{ $question->body }}</span>
                            <button class="button button-plain button-more questionrichtext-more">显示全部</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="questionheader-side">
                <div class="questionheader-follow-status">
                    <div class="questionfollowstatus">
                        <div class="numberboard questionfollowstatus-counts">
                            <button class="button numberboard-item button-plain" type="button">
                                <div class="numberboard-value">{{ $question->watchers_count }}</div>
                                @if ($question->watched)
                                    <div class="numberboard-name">已关注</div>
                                @else
                                    <div class="numberboard-name">关注</div>
                                @endif
                            </button>
                            <div class="numberboard-divider"></div>
                            <div class="numberboard-item">
                                <div class="numberboard-value">{{ $question->views_count }}</div>
                                <div class="numberboard-name">浏览</div>
                            </div>
                        </div>
                        @if (!$question->invitations->isempty())
                        <button class="button questionfollowstatus-people button-plain" type="button">
                            <span class="questionfollowstatus-people-tip">
                                已邀请悬赏:
                                <span class="userlink">
                                    <img class="avatar avatar--round" width="30px" height="30px" src="http://blog.jsonleex.com/icon/lx.png" alt="">
                                </span>
                                jsonleex
                            </span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="questionheader-footer">
            <div class="questionheader-footer-inner">
                <div class="questionheader-main questionheader-footer-main">
                    <span class="questionheader-onlook">￥0.0围观</span>
                    <div class="questionheaderactions">
                        <div class="questionheader-comment">
                            <button class="button button-plain" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                {{ $question->comments_count }} 评论
                            </button>
                        </div>
                        <div class="popover sharemenu">
                            <div>
                                <button class="button button-plain" type="button">
                                   <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                                    分享
                                </button>
                            </div>
                        </div>
                        <button class="button button-plain" type="button">
                           <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bianji2"></use></svg>
                            编辑
                        </button>
                        <button class="button button-plain" type="button">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>
                            举报
                        </button>
                        <div class="popover">
                            <button class="button button-plain" type="button" id="popover-6485-72543-toggle" aria-haspopup="true" aria-expanded="false" aria-owns="popover-6485-72543-content">
                                <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="questionheader-actions"></div>
                </div>
                <div class="questionheader-side">
                    <div class="question-button-group">
                        <button class="button button-primary button-blue" type="button">关注</button>
                        <!-- <button class="button button--grey" type="button">已关注</button> -->
                        <button class="button button-blue" type="button">写回答</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /question-header -->
    <!-- quesition-main -->
    <div class="question-main">
        <div class="question-main-l">
            <div class="question-answers">
                <div class="question-answers-list">
                    <div class="question-answers-list-header">
                        <h4 class="headertxt">30个回答</h4>
                        <div data-value="" class="zy_select t_c gap12 ">
                            <span>默认排序</span>
                            <ul>
                                <li data-value="user" class="active">默认排序</li>
                                <li data-value="org">时间排序</a>
                                </li>
                            </ul>
                            <i></i>
                        </div>
                    </div>
                    <div>
                    @foreach ($question->invitation_answers as $answer)
                        <div class="list-item">
                            <div class="list-item-header">
                                <span class="userlink authorinfo-avatarwrapper">
                                    <img class="avatar avatar--round" width="44" height="44" src="http://blog.jsonleex.com/icon/lx.png" alt="">
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
                                    <span>{{ $answer->body }}</span>
                                    <button class="button button-plain button-more"><a href="{{ route('pc:answeread', $answer->id) }}">查看详情</a></button>
                                </div>
        <div class="questionheader-footer">
            <div class="questionheader-footer-inner">
                <div class="questionheader-main questionheader-footer-main">
                    {{-- <span class="questionheader-onlook">￥0.0围观</span> --}}
                    <div class="questionheaderactions">
                        <div class="questionheader-comment">
                            <button class="button button-plain" type="button">
                                <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                                {{ $answer->comments_count }} 评论
                            </button>
                        </div>
                        <div class="popover sharemenu">
                            <div>
                                <button class="button button-plain" type="button">
                                    <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                    </svg>
                                    分享
                                </button>
                            </div>
                        </div>
                        <button class="button button-plain" type="button">
                            <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            编辑
                        </button>
                        <button class="button button-plain" type="button">
                            <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            举报
                        </button>
                        <div class="popover">
                            <button class="button button-plain" type="button" id="popover-6485-72543-toggle" aria-haspopup="true" aria-expanded="false" aria-owns="popover-6485-72543-content">
                                <svg class="icon icon-left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="questionheader-actions"></div>
                </div>
                {{-- <div class="questionheader-side">
                    <div class="question-button-group">
                        <button class="button button-primary Button-blue" type="button">关注</button>
                        <!-- <button class="button button-grey" type="button">已关注</button> -->
                        <button class="button button-blue" type="button">写回答</button>
                    </div>
                </div> --}}
            </div>
        </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="question-main-r"></div>
    </div>
    <!-- /quesition-main -->
</div>
@endsection