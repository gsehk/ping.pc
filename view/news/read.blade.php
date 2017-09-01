@section('title')
    {{ $news['title'] }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
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
                    <img src="{{ $routes['resource'] }}/images/zixun-left.png"/>
                    <div class="subject_content">{{ $news['subject'] }}</div>
                    <img src="{{ $routes['resource'] }}/images/zixun-right.png" class="right"/>
                </div>

                <div class="detail_content">{!! $news['content'] !!}</div>

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
                </div>

                {{-- comment  --}}
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
                {{-- /comment  --}}

                {{-- 打赏 --}}
                <div class="detail_pay">
                </div>

                {{-- 相关推荐 --}}
                <div class="detail_recommend">
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
<script>
$(function(){

setTimeout(function() {
    scroll.init({
        container: '#comment_box',
        loading: '.detail_comment',
        url: '/news/'+{{$news->id}}+'/comments'
    });
}, 300);

$('#J-comment-news').on('click', function(){
    if (MID == 0) {
        window.location.href = '/passport/index';
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
    'bdUrl' : window.location.href,
    'bdPic' : '{{ asset('zhiyicx/plus-component-pc/images/default_cover.png') }}'
});
});

</script>
@endsection
