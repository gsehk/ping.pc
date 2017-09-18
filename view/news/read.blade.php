@section('title')
    {{ $news['title'] }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getImageUrl;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/markdown/css/editormd.css') }}"/>
@endsection

@section('content')
    <div class="left_container">
        <div class="news_left">
            <div class="detail_body">
                <div class="detail_title">
                    {{ $news['title'] }}
                </div>

                <div class="detail_info">
                    <a href="javascript:;" class="cates_span">{{ $news['category']['name'] or '默认' }}</a>
                    <span>{{ $news['from'] }}  ·  {{ $news['hits'] }}浏览  ·  {{ getTime($news['created_at']) }}</span>
                </div>


                <div class="detail_subject">
                    <img src="{{ asset('zhiyicx/plus-component-pc/images/zixun-left.png') }}"/>
                    <div class="subject_content">{{ $news['subject'] }}</div>
                    <img src="{{ asset('zhiyicx/plus-component-pc/images/zixun-right.png') }}" class="right"/>
                </div>

                <div class="detail_content markdown-body editormd-preview-container">
                {!! Parsedown::instance()->setMarkupEscaped(true)->text($news->content) !!}
                </div>

                <div class="detail_share">
                    <span id="collect{{ $news->id }}" rel="{{ $news->collect_count }}">
                        @if(!$news->has_collect)
                        <a href="javascript:;" onclick="collect.addCollect('{{ $news->id }}')">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                            <font class="cs">{{ $news->collect_count }}</font>收藏
                        </a>
                        @else
                        <a href="javascript:;" onclick="collect.delCollect('{{ $news->id }}');" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                            <font class="cs">{{ $news->collect_count }}</font>收藏
                        </a>
                        @endif
                    </span>
                    <span id="digg{{ $news->id }}" rel="{{ $news->digg_count }}">
                        @if(!$news->has_like)
                        <a href="javascript:;" onclick="digg.addDigg('{{ $news->id }}');">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                            <font class="ds">{{ $news->digg_count }}</font>人喜欢
                        </a>
                        @else
                        <a href="javascript:;" onclick="digg.delDigg('{{ $news->id }}');" class="act">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                            <font class="ds">{{ $news->digg_count }}</font>人喜欢
                        </a>
                        @endif
                    </span>
                    <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                        分享至：
                        <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                        <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                        <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                    </div>

                    {{-- 打赏 --}}
                    <div class="reward-box">
                        <p><button class="btn btn-warning btn-lg" onclick="rewarded.show({{$news->id}}, 'news')">打 赏</button></p>
                        <div class="reward-user">
                        <p class="reward-info tcolor">
                            <font color="#F76C6A">{{$news['reward']['count']}} </font>次打赏，共
                            <font color="#F76C6A">{{$news['reward']['amount'] or 0}} </font>元
                        </p>
                        @if (!$news->rewards->isEmpty())
                        @foreach ($news->rewards as $reward)
                            <div class="user-item">
                                <img class="lazy round" data-original="{{ $reward['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" alt="avatar" width="42" />
                                @if ($reward['user']['sex'])
                                    <img class="sex-icon" src="{{ asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                                @endif
                            </div>
                        @endforeach
                            <span class="more-user"></span>
                        @endif
                        </div>
                    </div>
                </div>

                {{-- 相关推荐 --}}
                @if (!$news_rel->isEmpty())
                <div class="detail_recommend">
                    <p class="rel_title">相关推荐</p>
                    <div class="rel_tags">
                        @foreach ($news->tags as $tag)
                        <span>{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    @foreach ($news_rel as $rel)
                    <div class="rel_news_item clearfix">
                         <div class="rel_news_img">
                              <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}">
                                   <img class="lazy" width="180" height="130" data-original="{{ getImageUrl($rel['image'], 180, 130)}}"/>
                              </a>
                         </div>
                         <div class="rel_news_word">
                              <a href="{{ route('pc:newsread', ['news_id' => $rel['id']]) }}">
                                   <div class="news_title"> {{ $rel['title'] }} </div>
                              </a>
                              <p>{{ $rel['subject'] }}</p>
                              <div class="news_bm">
                                   <a href="javascript:;" class="cates_span">{{ $rel['category']['name'] }}</a>
                                   <span>{{ $rel['from'] }}  ·  {{ $rel['hits'] }}浏览  ·  {{ getTime($rel['created_at']) }}</span>
                              </div>
                         </div>
                    </div>
                    @endforeach
                </div>
                @endif



                {{-- 评论  --}}
                <div class="detail_comment">
                    <div class="comment_title"><span class="comment_count">{{$news->comment_count}}</span>人评论</div>
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
                                class="commnet_btn"
                                id="J-comment-news"
                                data-args="to_uid=0&row_id={{$news->id}}"
                                to_comment_id="0"
                                to_uid="0"
                            >评论</button>
                        </div>
                    </div>
                    <div class="comment_list" id="comment_box">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right_container">
        <div class="news_release_btn">
            <a href="{{ route('pc:newsrelease') }}">
                <span>
                    <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-feiji"></use></svg>投稿
                </span>
            </a>
        </div>
        {{-- 近期热点 --}}
        @include('pcview::widgets.hotnews')

        {{-- 广告位 --}}
        @include('pcview::widgets.ads', ['space' => 'pc:news:right', 'type' => 1])
    </div>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.news.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/markdown/lib/marked.js') }}"></script>
<script>
$(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});

    setTimeout(function() {
        scroll.init({
            container: '#comment_box',
            loading: '.detail_comment',
            url: '/news/'+{{$news->id}}+'/comments'
        });
    }, 300);

    $('#J-reward-btn').on('click', function(){
        var html = '<div class="reward-popups">'+
            '<p class="ucolor font14">选择打赏金额</p>'+
            '<div class="reward-sum">'+
                '<label class="opt tcolor" for="sum1">¥1.00'+
                    '<input class="hide" id="sum1" type="radio" name="sum" value="1">'+
                '</label>'+
                '<label class="opt tcolor active" for="sum5">¥5.00'+
                    '<input class="hide" id="sum5" type="radio" name="sum" value="5" checked>'+
                '</label>'+
                '<label class="opt tcolor" for="sum10">¥10.00'+
                    '<input class="hide" id="sum10" type="radio" name="sum" value="10">'+
                '</label>'+
            '</div>'+
            '<p><input class="custom-sum" type="number" min="0" name="custom" placeholder="自定金额，必须是整数"></p>'+
            '<div class="reward-btn-box">'+
                '<button class="btn btn-default mr20" onclick="ly.close();">&nbsp;取 消&nbsp;</button>'+
                '<button class="btn btn-primary news" onclick="rewarded.weibo(this, {{$news->id}});">&nbsp;打 赏&nbsp;</button>'+
            '</div>'+
        '</div>';
        ly.loadHtml(html, '', '350px', '300px;');
        $('.reward-sum label').on('click', function(){
            $('.reward-sum label').removeClass('active');
            $(this).addClass('active');
        })
    });

    $('#J-comment-news').on('click', function(){
        if (MID == 0) {
            window.location.href = '/passport/login';
            return false;
        }
        var attrs = urlToObject($(this).data('args'));
        comment.addComment(attrs, this);
    });

    // 近期热点
    if($('.time_menu li a').length > 0) {
        $('.time_menu li').hover(function() {
            var type = $(this).attr('type');

            $(this).siblings().find('a').removeClass('hover');
            $(this).find('a').addClass('hover');

            $('.hot_news_list div').hide();
            $('#' + type).show();
        })
    }

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{ $news['title'] }}',
        'bdDesc' : '{{ $news['title'] }}',
        'bdUrl' : window.location.href
    });
});

</script>
@endsection
