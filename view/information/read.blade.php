@extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="dy_cont del_top">
        <div class="del_left">
            <div class="del_title">{{ $title }}</div>
            <div class="top_list">
                @if (!empty($link))
                @foreach ($link as $li)
                <a href="javascript:;" class="top_list_span">{{ $li['name'] }}</a>
                @endforeach
                @endif
                <a href="{{ route('pc:myFeed', ['user_id'=>$user['id']]) }}">{{ $user['name'] }}</a>
                <div class="del_top_r">
                    <span class="del_time">{{ $created_at }}</span>
                </div>
            </div>
            <div class="zx_top">
                <img src="{{ $routes['resource'] }}/images/zixun-left.png" class="zx_l"/>
                <div class="zx_word">{{ $subject or '...'}}</div>
                <img src="{{ $routes['resource'] }}/images/zixun-right.png" class="zx_r"/>
            </div>
            <div class="post_content">
                {!!$content!!}
            </div>
            <div class="del_pro">
                <span id="collect{{$id}}" rel="{{ count($collection) }}">
                    @if($is_collect_news <= 0)
                    <a href="javascript:;" onclick="collect.addCollect('{{$id}}')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">{{ count($collection) }}</font>收藏</a>
                    @else 
                    <a href="javascript:;" onclick="collect.delCollect('{{$id}}');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">{{ count($collection) }}</font>收藏</a>
                    @endif
                </span>
                <span id="digg{{ $id }}" rel="{{ $digg_count }}">
                    @if($is_digg_news <= 0)
                    <a href="javascript:;" onclick="digg.addDigg('{{$id}}');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">{{ $digg_count }}</font>人喜欢</a>
                    @else 
                    <a href="javascript:;" onclick="digg.delDigg('{{$id}}');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">{{ $digg_count }}</font>人喜欢</a>
                    @endif
                </span>
                <div class="del_share bdsharebuttonbox share_feedlist clearfix" data-tag="share_feedlist">
                    分享至：
                    <a href="javascript:;" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="javascript:;" class="bds_tqq" data-cmd="sqq" title="分享到腾讯微博"></a>
                    <a href="javascript:;" class="bds_weixin" data-cmd="weixin" title="分享到朋友圈"></a>
                </div>
            </div>
            <div class="del_comment"><span class="comment_count">{{ $comment_count }}</span>人评论</div>
            <div class="comment-box">
                <textarea class="del_ta" id="mini_editor" placeholder="说点什么吧" onkeyup="checkNums(this, 255, 'nums');"></textarea>
                <div class="dy_company" style="margin: 0;">
                    <!-- <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span> -->
                    <span class="dy_cs" style="margin-left: 500px;">可输入<span class="nums">255</span>字</span>
                    <button class="dy_share a_link" id="J-comment-news" data-args="editor=#comment&box=#comment_detail&row_id={{$id}}&canload=0" to_comment_id="0" to_uid="0" addtoend="0">评论</button>
                </div>
            </div>
            <div class="delComment_cont" id="comment_detail"></div>
            <div class="del_right"></div>
        </div>
        <div class="del_right">
            <div class="delTop">
                <div class="delToP_left">
                    <div>
                        <img src="{{ $user['avatar'] }}" />
                    </div>
                </div>
                <div class="delTop_right">
                    <span><a href="{{ route('pc:myFeed', ['user_id'=>$user['id']]) }}">{{ $user['name'] }}</a></span>
                    <p class="txt-hide">{{ $user['intro'] or ''}}</p>
                </div>
                <ul class="del_ul">
                    <li style="border-right:1px solid #ededed;">
                        <a href="{{route('pc:article',['user_id'=>$author])}}">文章<span>{{ $news_count_count }}</span></a>
                    </li>
                    <li>
                        <a href="javascript:;">热门<span>{{ $hots }}</span></a>
                    </li>
                </ul>
                @foreach($news as $post)
                    <div class="del_rTop">
                        <span></span>
                        <a href="/information/read/{{ $post['id'] }}">{{ $post['title'] }}</a>
                    </div>
                @endforeach
            </div>
            @if(count($news) >= 3)<a href="{{route('pc:article',['user_id'=>$user['id']])}}" class="del_more">更多TA的文章</a>@endif
            
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time" id="j-recent-hot">
                    <li><a href="javascript:;" cid="1" class="week a_border">一周</a></li>
                    <li><a href="javascript:;" cid="2" class="meth">月度</a></li>
                    <li><a href="javascript:;" cid="3" class="moth">季度</a></li>
                </ul>
                <ul class="new_list" id="j-recent-hot-wrapp">
                    
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.news.js"></script>
<script src="{{ $routes['resource'] }}/js/module.bdshare.js"></script>
<script type="text/javascript">

$('#J-comment-news').on('click', function(){
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
    comment.addComment(after, this);
});

$(document).ready(function(){

  comment.init({row_id:'{{$id}}', canload:true});

  recent_hot(1); 
  $('#j-recent-hot a').on('click', function(){
        var cid = $(this).attr('cid');
        recent_hot(cid);
        $('#j-recent-hot a').removeClass('a_border');
        $(this).addClass('a_border');
  });
});
bdshare.addConfig('share', {
    "tag" : "share_feedlist",
    'bdText' : '{{ $title }}',
    'bdDesc' : '{{ $title }}',
    'bdUrl' : window.location.href,
    'bdPic' : '{{ $routes['resource'] }}/images/default_cover.png'
});
</script>
@endsection
