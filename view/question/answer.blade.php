@section('title') 回答详情 @endsection
@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/feed.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
@endsection

@section('content')
<div class="question_left_container">
    <div class="answer-detail-box bgwhite">
        <dl class="user-box clearfix">
            <dt class="fl relative">
                <a href="{{ route('pc:mine', $answer->user->id) }}">
                    <img class="round" src="{{ $answer->user->avatar  or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" width="60">
                    @if ($answer->user->verified)
                        <img class="role-icon" src="{{ $answer->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                    @endif
                </a>
            </dt>
            <dd class="fl body-box">
                <a href="{{ route('pc:mine', $answer->user->id) }}" class="tcolor">{{ $answer->user->name }}</a>
                <div class="user-tags">
                @if ($answer->user->tags)
                    @foreach ($answer->user->tags as $tag)
                        <span class="tag ucolor">{{ $tag->name }}</span>
                    @endforeach
                @endif
                </div>
            </dd>
        </dl>

        <div class="answer-body">
            {!! Parsedown::instance()->setMarkupEscaped(true)->text($answer->body) !!}
        </div>

        <div class="detail_share">
            <span id="J-collect{{ $answer->id }}" rel="{{ $answer->collect_count }}" status="{{(int) $answer->collected}}">
                @if($answer->collected)
                <a class="act" href="javascript:;" onclick="collected.init({{$answer->id}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                    <font class="cs">{{ $answer->collect_count }}</font> 人收藏
                </a>
                @else
                <a href="javascript:;" onclick="collected.init({{$answer->id}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                    <font class="cs">{{ $answer->collect_count }}</font> 人收藏
                </a>
                @endif
            </span>
            <span id="J-likes{{$answer->id}}" rel="{{ $answer->likes_count }}" status="{{(int) $answer->liked}}">
                @if($answer->liked)
                <a class="act" href="javascript:;" onclick="liked.init({{$answer->id}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                    <font>{{ $answer->likes_count }}</font> 人喜欢
                </a>
                @else
                <a href="javascript:;" onclick="liked.init({{$answer->id}}, 'question', 0);">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                    <font>{{ $answer->likes_count }}</font> 人喜欢
                </a>
                @endif
            </span>
            {{-- 第三方分享 --}}
            <div class="detail_third_share">
                分享至：
                @php
                    // 设置第三方分享图片
                        preg_match('/<img src="(.*?)".*?/', $answer->body, $imgs);
                        if (count($imgs) > 0) {
                            $share_pic = $imgs[1];
                        } else {
                            $share_pic = '';
                        }
                @endphp
                @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:answeread', ['answer' => $answer->id]), 'share_title' => addslashes($answer->body), 'share_pic' => $share_pic])
            </div>

            {{-- 打賞 --}}
            @php
                $rewards_info['count'] = $answer->rewarder_count;
                $rewards_info['amount'] = $answer->rewards_amount;
            @endphp
            @include('pcview::widgets.rewards' , ['rewards_data' => $answer->rewarders, 'rewards_type' => 'answer', 'rewards_id' => $answer->id, 'rewards_info' => $rewards_info])
        </div>
        <div class="detail_comment">
                <div class="comment_title"><span class="comment_count cs{{$answer->id}}"">{{$answer->comments_count}} </span>人评论</div>
                <div class="comment_box">
                    <textarea
                            class="comment_editor"
                            id="J-editor{{$answer->id}}"
                            placeholder="说点什么吧"
                            onkeyup="checkNums(this, 255, 'nums');"
                    ></textarea>
                    <div class="comment_tool">
                        <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                        <button
                            class="btn btn-primary"
                            id="J-button{{$answer->id}}"
                            onclick="QA.addComment({{$answer->id}}, 0)"
                        > 评 论 </button>
                    </div>
                </div>
                <div class="comment_list J-commentbox" id="J-commentbox{{$answer->id}}">

                </div>
            </div>
    </div>
</div>

<div class="right_container">
    <div class="answer-author">
        <div class="author-con">
            <div class="author-avatar"><a href="{{ route('pc:mine', $answer->user->id) }}"><img src="{{ $answer->user->avatar  or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" alt=""></a></div>
            <div class="author-right">
                <div class="author-name"><a href="{{ route('pc:mine', $answer->user->id) }}">{{ $answer->user->name }}</a></div>
                <div class="author-intro">{{ $answer->user->bio or '暂无简介~~'}}</div>
            </div>
        </div>
        <div class="author-count">
            <div>提问 <span>{{ $answer->user->extra->questions_count }}</span></div>
            <div>回答 <span>{{ $answer->user->extra->answers_count }}</span></div>
        </div>
        <div class="author-collect">
            @if($answer->user->hasFollower)
                <a href="javascript:;" id="follow" status="1">已关注</a>
            @else
                <a href="javascript:;" id="follow" class="followed" status="0">+关注</a>
            @endif
        </div>
    </div>

    {{-- 热门问题 --}}
    @include('pcview::widgets.hotquestions')
</div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.question.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/qrcode.js') }}"></script>
<script>
$(function(){
    setTimeout(function() {
        scroll.init({
            container: '.J-commentbox',
            loading: '.answer-detail-box',
            url: '/question/answer/{{$answer->id}}/comments',
            canload: true
        });
    }, 200);

    $("img.lazy").lazyload({effect: "fadeIn"});

    // 关注
    $('#follow').click(function(){
        var _this = $(this);
        var status = $(this).attr('status');
        var user_id = "{{ $answer->user->id }}";
        follow(status, user_id, _this, afterdata);
    });

    // 关注回调
    var afterdata = function(target){
        if (target.attr('status') == 1) {
            target.text('+关注');
            target.attr('status', 0);
            target.addClass('followed');
        } else {
            target.text('已关注');
            target.attr('status', 1);
            target.removeClass('followed');
        }
    }
})

</script>
@endsection