@extends('layouts.default')

@section('content')
<div class="dy_bg">
    <div class="dy_cont del_top">
        <div class="del_left">
            <div class="del_title">{{$title}}</div>
            <div class="top_list">
                <a href="javascript:;" class="top_list_span">视点</a>
                <a href="javascript:;">视点</a>
                <div class="del_top_r">
                    <span class="del_time">{{$created_at}}</span>
                </div>
            </div>
            <div class="post_content">
                {!!$content!!}
            </div>
            <div class="del_pro">
                <span><i class="icon iconfont icon-shoucang-copy1"></i>{{count($collection)}}收藏</span>
                <span><i class="icon iconfont icon-xihuan-white"></i>{{$digg_count}}人喜欢</span>
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
            <div class="del_comment"><span>{{$comment_count}}</span>人评论</div>
            <div>
                <textarea class="del_ta" placeholder="说点什么吧"></textarea>
                <div class="dy_company" style="padding-left:0;">
                    <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span>
                    <span class="dy_cs" style="margin:10px auto auto 420px;">可输入<span>255</span>字</span>
                    <button class="dy_share a_link" style="margin-right: 0; float: right;">评论</button>
                </div>
            </div>
            <div class="delComment_cont">
                <div class="delComment_list">
                    <div class="comment_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="c_leftImg" />
                    </div>
                    <div class="comment_right">
                        <span class="del_ellen">Ellen</span>
                        <span class="c_time">5分钟前</span>
                        <i class="icon iconfont icon-gengduo-copy"></i>
                        <a href="javascript:;">
                            大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头
                            <span class="del_huifu">回复</span>
                        </a>
                    </div>
                </div>
                <div class="delComment_list">
                    <div class="comment_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="c_leftImg" />
                    </div>
                    <div class="comment_right">
                        <span class="del_ellen">Ellen</span>
                        <span class="c_time">5分钟前</span>
                        <i class="icon iconfont icon-gengduo-copy"></i>
                        <div class="del_reply">
                            回复
                            <span class="del_ellen" style="font-weight:bold;margin:0 5px;">Ellen</span>
                            晚上好
                        </div>
                    </div>
                </div>
                <div class="delComment_list">
                    <div class="comment_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="c_leftImg" />
                    </div>
                    <div class="comment_right">
                        <span class="del_ellen">Ellen</span>
                        <span class="c_time">5分钟前</span>
                        <i class="icon iconfont icon-gengduo-copy"></i>
                        <a href="javascript:;">
                            大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头
                            <span class="del_huifu">回复</span>
                        </a>
                        <div>
                            <textarea class="del_ta" placeholder="说点什么吧"></textarea>
                            <div class="dy_company" style="padding-left:0;">
                                <span class="fs-14">
                                    <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-biaoqing"></use></svg>
                                    表情
                                </span>
                                <span class="dy_cs" style="margin:10px auto auto 360px;">可输入<span>255</span>字</span>
                                <button class="dy_share a_link" style="margin-right: 0; float: right;margin-top:0;">评论</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="delComment_cont">
                    <div class="delComment_list">
                        <div class="comment_left">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="c_leftImg" />
                        </div>
                        <div class="comment_right">
                            <span class="del_ellen">Ellen</span>
                            <span class="c_time">5分钟前</span>
                            <i class="icon iconfont icon-gengduo-copy"></i>
                            <a href="javascript:;">
                                大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头
                                <span class="del_huifu">回复</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="del_right"></div>
        </div>
        <div class="del_right">
            <div class="delTop">
                <div class="delToP_left">
                    <div> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" /></div>
                </div>
                <div class="delTop_right">
                    <span>大姐头</span>
                    <p>大师大师大师大师大师大师大师</p>
                </div>
                <ul class="del_ul">
                    <li style="border-right:1px solid #ededed;">
                        <a href="javascript:;">文章<span>16</span></a>
                    </li>
                    <li>
                        <a href="javascript:;">热门<span>10</span></a>
                    </li>
                </ul>
                <div class="del_rTop">
                    <span></span>
                    <a href="javascript:;">京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉</a>
                </div>
                <div class="del_rTop">
                    <span></span>
                    <a href="javascript:;">京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉</a>
                </div>
                <div class="del_rTop">
                    <span></span>
                    <a href="javascript:;">京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉</a>
                </div>
                <div class="del_rTop">
                    <span></span>
                    <a href="javascript:;">京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉京东亏损拉</a>
                </div>
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
                    <div class='loading'><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/loading.png') }}" class='load'>加载中</div>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/news.js') }}"></script>
@endsection
