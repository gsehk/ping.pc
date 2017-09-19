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
            <span id="collect{{$answer->id}}" rel="1">
                @if($answer->collected)
                    <a href="javascript:;" onclick="collect.delCollect({{$answer->id}}, 'read');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">0 </font>人收藏
                    </a>
                    @else
                    <a href="javascript:;" onclick="collect.addCollect({{$answer->id}}, 'read')">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">0 </font>人收藏
                    </a>
                @endif
            </span>
            <span id="digg{{$answer->id}}" rel="{{$answer->likes_count}}">
                @if($answer->liked)
                    <a href="javascript:;" onclick="digg.delDigg({{$answer->id}}, 'read');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font class="ds">{{ $answer->likes_count }} </font>人喜欢
                    </a>
                    @else
                    <a href="javascript:;" onclick="digg.addDigg({{$answer->id}}, 'read');">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font class="ds">{{ $answer->likes_count }} </font>人喜欢
                    </a>
                @endif
            </span>
            <div class="del_share bdsharebuttonbox share_feedlist clearfix bdshare-button-style0-16" data-tag="share_feedlist" data-bd-bind="1505717855934">
                分享至：
                <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
            </div>
            <div class="reward-box">
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
            <div class="comment_title"><span class="comment_count">{{ $answer->comments_count }} </span>人评论</div>
            <div class="comment_box">
                <textarea
                    class="comment_editor"
                    id="mini_editor"
                    placeholder="说点什么吧"
                    onkeyup="checkNums(this, 255, 'nums');"
                ></textarea>
                <div class="comment_tool">
                    <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                    <button
                        class="btn btn-primary"
                        id="J-comment-answer"
                        row_id="{{$answer->id}}"
                        to_uid="0"
                        onclick="comment.publish(this)"
                    > 评 论 </button>
                </div>
            </div>
            <div class="comment_list" id="comment_box">

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
<script>
$(function(){
    setTimeout(function() {
        scroll.init({
            container: '#comment_box',
            loading: '.answer-detail-box',
            url: '/question/answer/{{$answer->id}}/comments',
            canload: true
        });
    }, 200);

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{$answer->body}}',
        'bdDesc' : '',
        'bdUrl' : window.location.href
    });

    $("img.lazy").lazyload({effect: "fadeIn"});
})

</script>
@endsection