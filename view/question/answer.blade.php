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
            <dt class="fl"><img src="http://tss.io/api/v2/users/2/avatar" width="60"></dt>
            <dd class="fl body-box">
                <span class="tcolor">root</span>
                <div class="user-tags">
                @if ($answer->user->tags)
                    @foreach ($answer->user->tags as $tag)
                        <span class="tag ucolor">{{ $tag->name }}</span>
                    @endforeach
                @endif
                </div>
            </dd>
        </dl>

        <div class="answer-body">{{ $answer->body }}</div>

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
            <div class="reward_cont">
                <p><button class="btn btn-warning btn-lg" onclick="rewarded.show({{$answer->id}}, 'answer')">打 赏</button></p>
                <p class="reward-info tcolor">
                    <font color="#F76C6A">{{ $answer->rewarder_count }} </font>次打赏，共
                    <font color="#F76C6A">{{ $answer->rewards_amount }} </font>元
                </p>
                <div class="reward-user">
                    @if (!$answer->rewarders->isEmpty())
                        @foreach ($answer->rewarders as $reward)
                            <div class="user-item">
                                <img class="lazy round" data-original="{{ $reward->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" alt="avatar" width="42" />
                                @if ($reward->user->sex)
                                    <img class="sex-icon" src="{{ asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                                @endif
                            </div>
                        @endforeach
                        <span class="more-user" onclick="rewarded.list({{$answer->id}}, 'answer');"></span>
                    @endif
                </div>
            </div>
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
})

</script>
@endsection