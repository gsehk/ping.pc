    @extends('pcview::layouts.default')

@section('content')
<div class="dy_bg">
    <div class="dy_cont del_top">
        <div class="del_left">
            @if($feed['storages'])
            <div style="background: rgb(247, 248, 250);" id="layer-photos-demo">
            @foreach($feed['storages'] as $store)
            <img src="{{ $routes['storage']}}{{$store['storage_id'] }}" class="img-responsive" style="margin: 0 auto;" />
            @endforeach
            </div>
            @endif
            <div class="post_content">
                {!!$feed['feed_content']!!}
            </div>
            <div class="del_pro">
                <span id="collect{{$feed['feed_id']}}" rel="{{ $tool['feed_collection_count'] }}">
                    @if($tool['is_collection_feed'] <= 0)
                    <a href="javascript:;" onclick="collect.addCollect({{$feed['feed_id']}}, 'read')"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font>{{$tool['feed_collection_count']}}</font>人收藏</a>
                    @else 
                    <a href="javascript:;" onclick="collect.delCollect({{$feed['feed_id']}}, 'read');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font>{{$tool['feed_collection_count']}}</font>人收藏</a>
                    @endif
                </span>
                <span id="digg{{ $feed['feed_id'] }}" rel="{{ $tool['feed_digg_count'] }}">
                    @if($tool['is_digg_feed'] <= 0)
                    <a href="javascript:;" onclick="digg.addDigg('{{ $feed['feed_id'] }}','read');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font>{{$tool['feed_digg_count']}}</font>人喜欢</a>
                    @else 
                    <a href="javascript:;" onclick="digg.delDigg('{{ $feed['feed_id'] }}','read');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font>{{$tool['feed_digg_count']}}</font>人喜欢</a>
                    @endif
                </span>
                <div class="del_share">
                    分享至：
                    <svg class="icon svdel_g1" aria-hidden="true">
                        <use xlink:href="#icon-weibo"></use>
                    </svg>
                    <svg class="icon svdel_g2" aria-hidden="true">
                        <use xlink:href="#icon-qq"></use>
                    </svg>
                    <svg class="icon svdel_g3" aria-hidden="true">
                        <use xlink:href="#icon-weixin"></use>
                    </svg>
                </div>
            </div>
            <div class="del_comment"><span>{{ $tool['feed_comment_count'] }}</span>人评论</div>
            <div class="comment-box">
                <textarea class="del_ta" id="mini_editor" placeholder="说点什么吧"></textarea>
                <div class="dy_company" style="margin: 0;">
                    <!-- <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span> -->
                    <span class="dy_cs" style="margin-left:420px;">可输入<span>255</span>字</span>
                    <button class="dy_share a_link" id="J-comment-news" data-args="editor=#comment&box=#comment_detail&row_id={{ $feed['feed_id'] }}&canload=0" to_comment_id="0" to_uid="0" addtoend="0">评论</button>
                </div>
            </div>
            <div class="delComment_cont" id="comment_detail"></div>
            <div class="del_right"></div>
        </div>
        <div class="del_right">
            <div class="delTop">
                <div class="delToP_left">
                    <div> <img src="{{ $routes['resource'] }}/images/cicle.png" /></div>
                </div>
                <div class="delTop_right">
                    <span>{{ $news['user']['name'] }}</span>
                    <p class="txt-hide">{{ $news['user']['name'] or '' }}</p>
                </div>
                <ul class="del_ul">
                    <li style="border-right:1px solid #ededed;">
                        <a href="javascript:;">文章<span>{{ $news['newsNum'] }}</span></a>
                    </li>
                    <li>
                        <a href="javascript:;">热门<span>{{ $news['hotsNum'] }}</span></a>
                    </li>
                </ul>
                @foreach($news['list'] as $post)
                    <div class="del_rTop">
                        <span></span>
                        <a href="javascript:;">{{ $post['title'] }}</a>
                    </div>
                @endforeach
            </div>
            <a href="javascript:;" class="del_more">更多他的文章</a>
            
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time" id="j-recent-hot">
                    <li><a href="javascript:;" class="week a_border">本周</a></li>
                    <li><a href="javascript:;" class="meth">当月</a></li>
                    <li><a href="javascript:;" class="moth">季度</a></li>
                </ul>
                <ul class="new_list" id="j-recent-hot-wrapp">
                    <div class='loading'><img src="{{ $routes['resource'] }}/images/loading.png" class='load'>加载中</div>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/layer/layer.js"></script>
<script type="text/javascript">
layer.photos({
  photos: '#layer-photos-demo'
  ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
}); 
$('#J-comment-news').on('click', function(){
    var attrs = urlToObject($(this).data('args'));
    attrs.to_uid = $(this).attr('to_uid');
    attrs.addToEnd = $(this).attr('addtoend');
    attrs.to_comment_id = $(this).attr('to_comment_id');
    console.log(attrs);
    comment.init(attrs);

    var _this = this;
    var after = function(){
        $(_this).attr('to_uid','0');
        $(_this).attr('to_comment_id','0');
    }
    comment.addReadComment(after, this);
});

$(document).ready(function(){

  comment.init({row_id:'{{$feed['feed_id']}}', canload:true});

});
</script>
@endsection
