@extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="dy_cont del_top">
        <div class="del_left">
            @if($feed->images)
            <div style="background: rgb(247, 248, 250);" id="layer-photos-demo">
            @foreach($feed->images as $store)
            <img data-original="{{ $routes['storage']}}{{$store['file'] }}?w=675&h=380" 
                class="lazy img-responsive" 
                style="margin: 0 auto;width: 100%;" />
            @endforeach
            </div>
            @endif
            <div class="post_content">
                {!!$feed->feed_content!!}
            </div>
            <div class="del_pro">
                <span id="collect{{$feed->id}}" rel="{{ $feed->collect_count }}">
                    @if($feed->has_collect)
                    <a href="javascript:;" onclick="collect.delCollect({{$feed->id}}, 'read');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">{{$feed->collect_count}}</font>人收藏</a>
                    @else 
                    <a href="javascript:;" onclick="collect.addCollect({{$feed->id}}, 'read')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">{{$feed->collect_count}}</font>人收藏</a>
                    @endif
                </span>
                <span id="digg{{ $feed->id }}" rel="{{ $feed->like_count }}">
                    @if($feed->has_like)
                    <a href="javascript:;" onclick="digg.delDigg('{{ $feed->id }}','read');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">{{$feed->like_count}}</font>人喜欢</a>
                    @else 
                    <a href="javascript:;" onclick="digg.addDigg('{{ $feed->id }}','read');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">{{$feed->like_count}}</font>人喜欢</a>
                    @endif
                </span>
                <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                    分享至：
                    <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                    <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                </div>
            </div>
            <div class="del_comment"><span class="comment_count">{{ $feed->feed_comment_count }}</span>人评论</div>
            <div class="comment-box">
                <textarea class="del_ta" id="mini_editor" placeholder="说点什么吧" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                <div class="dy_company" style="margin: 0;">
                    {{-- <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span> --}}
                    <span class="dy_cs" style="margin-left: 500px;">可输入<span class="nums">255</span>字</span>
                    <button class="dy_share a_link" id="J-comment-feed" data-args="editor=#comment&box=#comment_detail&row_id={{ $feed->id }}&canload=0" to_comment_id="0" to_uid="0" addtoend="0">评论</button>
                </div>
            </div>
            <div class="delComment_cont" id="comment_detail"></div>
            <div class="del_right"></div>
        </div>
        <div class="del_right">
            <div class="delTop">
                <div class="delToP_left">
                    <div> <img src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png' }}" /></div>
                </div>
                <div class="delTop_right">
                    <span>{{ $user['name'] }}</span>
                    <p class="txt-hide">{{ $user['bio'] or '这家伙很懒，什么都没留下！' }}</p>
                </div>
                <ul class="del_ul">
                    <li style="border-right:1px solid #ededed;">
                        <a href="{{ Route('pc:article',['user_id'=>$feed->user_id]) }}">文章<span>{{ $news['news_count'] }}</span></a>
                    </li>
                    <li>
                        <a href="javascript:;">热门<span>{{ $news['hots_count'] }}</span></a>
                    </li>
                </ul>
                @foreach($news['list'] as $post)
                    <div class="del_rTop">
                        <span></span>
                        <a href="{{ Route('pc:newsRead', $post->id) }}">{{ $post->title }}</a>
                    </div>
                @endforeach
            </div>
            @if($news['list']->count() >= 3)<a href="{{ Route('pc:mine',['user_id'=>$feed->user_id]) }}" class="del_more">更多TA的文章</a>@endif
            
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time" id="j-recent-hot">
                    <li class="infR_time_3"><a href="javascript:;" cid="1" class="week a_border">一周</a></li>
                    <li class="infR_time_3"><a href="javascript:;" cid="2" class="meth">月度</a></li>
                    <li class="infR_time_3"><a href="javascript:;" cid="3" class="moth">季度</a></li>
                </ul>
                <ul class="new_list" id="j-recent-hot-wrapp">
                    <div class="list list1">
                        @if(!$news['week']->isEmpty())
                        @foreach($news['week'] as $week)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $week->id) }}">{{$week->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                    <div class="list list2">
                        @if(!$news['month']->isEmpty())
                        @foreach($news['month'] as $month)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $month->id) }}">{{$month->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                    <div class="list list3">
                        @if(!$news['quarter']->isEmpty())
                        @foreach($news['quarter'] as $quarter)
                            <li>
                                <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                                <a href="{{ Route('pc:newsRead', $quarter->id) }}">{{$quarter->title}}</a>
                            </li>
                        @endforeach
                        @else
                            <div class="loading">暂无相关信息</div>
                        @endif
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/js/module.bdshare.js"></script>
<script src="{{ $routes['resource'] }}/layer/layer.js"></script>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
  ,move: false
}); 

$(document).ready(function(){
    $("img.lazy").lazyload({effect: "fadeIn"});
    comment.init({row_id:'{{$feed->id}}', canload:true});

    $('#j-recent-hot a').hover(function(){
        $('.list').hide();
        $('.list'+$(this).attr('cid')).show();
        $('#j-recent-hot a').removeClass('a_border');
        $(this).addClass('a_border');
    });

    $('#J-comment-feed').on('click', function(){
        if (MID == 0) {
            window.location.href = '/passport/index';
            return false;
        }
        var attrs = urlToObject($(this).data('args'));
        attrs.to_uid = $(this).attr('to_uid');
        attrs.addToEnd = $(this).attr('addtoend');
        attrs.to_comment_id = $(this).attr('to_comment_id');
        comment.init(attrs);

        var _this = this;
        var after = function(){
            $(_this).attr('to_uid','0');
            $(_this).attr('to_comment_id','0');
        }
        comment.addReadComment(after, this);
    });

    bdshare.addConfig('share', {
        "tag" : "share_feedlist",
        'bdText' : '{{$feed['share_desc']}}',
        'bdDesc' : '{{$feed['share_desc']}}',
        'bdUrl' : window.location.href,
        'bdPic' : '{{ $routes['resource'] }}/images/default_cover.png'
    });
});


</script>
@endsection
