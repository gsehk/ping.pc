@section('title')
    {{ $news['title'] }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/news.css') }}"/>
@endsection

@section('content')
    <div class="news_left">
        <div class="detail_body">
            <div class="detail_title">
                {{ $news['title'] }}
            </div>

            <div class="detail_info">
                <a href="javascript:;" class="cates_span">{{ $news['category']['name'] }}</a>
                <span>{{ $news['from'] }}  ·  {{ $news['hits'] }}浏览  ·  {{ getTime($news['created_at']) }}</span>
            </div>


            <div class="detail_subject">
                <img src="{{ $routes['resource'] }}/images/zixun-left.png"/>
                <div class="subject_content">{{ $news['subject'] }}</div>
                <img src="{{ $routes['resource'] }}/images/zixun-right.png" class="right"/>
            </div>

            <div class="detail_content">{!! $news['content'] !!}</div>

            <div class="detail_share">
                <span id="collect{{ $news['id'] }}" rel="{{ $news['collect_count'] }}">
                    @if(!$news['has_collect'])
                    <a href="javascript:;" onclick="collect.addCollect('{{ $news['id'] }}')">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                        <font class="cs">{{ $news['collect_count'] }}</font>收藏
                    </a>
                    @else 
                    <a href="javascript:;" onclick="collect.delCollect('{{ $news['id'] }}');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                        <font class="cs">{{ $news['collect_count'] }}</font>收藏
                    </a>
                    @endif
                </span>
                <span id="digg{{ $news['id'] }}" rel="{{ $news['digg_count'] }}">
                    @if(!$news['collect_count'])
                    <a href="javascript:;" onclick="digg.addDigg('{{ $news['id'] }}');">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                        <font class="ds">{{ $news['digg_count'] }}</font>人喜欢
                    </a>
                    @else 
                    <a href="javascript:;" onclick="digg.delDigg('{{ $news['id'] }}');" class="act">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg>
                        <font class="ds">{{ $news['digg_count'] }}</font>人喜欢
                    </a>
                    @endif
                </span>
                <!-- <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                    分享至：
                    <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                    <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                </div> -->
            </div>

            <!-- 打赏 -->
            <div class="detail_pay">
            </div>

            <!-- 相关推荐 -->
            <div class="detail_recommend">
            </div>

        </div>
    </div>

    <div class="right_container">
    </div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.news.js"></script>
<script src="{{ $routes['resource'] }}/js/module.bdshare.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{ $news['title'] }}',
        'bdDesc' : '{{ $news['title'] }}',
        'bdUrl' : window.location.href,
        'bdPic' : '{{ $routes['resource'] }}/images/default_cover.png'
    });
});

</script>
@endsection
