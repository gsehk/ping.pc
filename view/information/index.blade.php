@extends('layouts.default')

@section('content')
<div class="dy_bg">
    <div class="in_cont">
        <div class="inT_l">
            <div class="inT_title">
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                <span>大姐头</span>
            </div>
            <div class="inT_word">大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师</div>
            <div class="inT_line"></div>
            <div class="inT_list">
                <span>大姐头大姐头大姐头大姐头大姐头大姐头</span>
                <span>大姐头大姐头大姐头大姐头大姐头大姐头</span>
                <span>大姐头大姐头大姐头大姐头大姐头大姐头</span>
                <span>大姐头大姐头大姐头大姐头大姐头大姐头</span>
                <span>大姐头大姐头大姐头大姐头大姐头大姐头</span>
            </div>
        </div>
        <div class="inT_c">
            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" />
        </div>
        <div class="inT_r">
            <div class="inR_top">
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" />
                <div class="inR_time">2017-4-10</div>
                <span class="inR_qd">每日签到</span>
                <div class="inR_lk">立即签到，赚取<span>5</span>积分</div>
            </div>
            <div class="inR_bottom">
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy-copy-copy"></use>
                    </svg>
                    <span>关注的人</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy"></use>
                    </svg>
                    <span>收藏的</span>
                </div>
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy"></use>
                    </svg>
                    <span>全部动态</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy"></use>
                    </svg>
                    <span>排行榜</span>
                </div>
                <div class="inR_bottom_list border_r">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy-copy-copy"></use>
                    </svg>
                    <span>我的动态</span>
                </div>
                <div class="inR_bottom_list">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-bofang1-copy-copy-copy"></use>
                    </svg>
                    <span>设置</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="inf_cont">
    <div class="inf_main">
        <div class="inf_left">
            <ul>
            @foreach ($cate as $post)
                @if ($loop->iteration < 10)
                <li><a href="javascript:;">{{$post['name']}}</a></li>
                @endif
            @endforeach
            </ul>
            <div id="news-list">
                <div class="inf_list">
                    <div class="inf_img">
                        <a href="{{Route('pc:newsdetail')}}">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" />
                        </a>
                    </div>
                    <div class="inf_word">
                        <a href="{{Route('pc:newsdetail')}}">
                            <div class="infW_title">大新闻成都三环绿化带竣工大新闻成都三环绿化带竣工大新闻成都三环绿化带竣工大新闻成都三环绿化带竣工大新闻成都三环绿化带竣工大新闻成都三环绿化带竣工</div>
                        </a>
                        <p>内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容</p>
                        <div class="inf_bm">
                            <span class="inf_time">环球网-5分钟前</span>
                            <span class="inf_comment">1评论<span>|</span>2收藏</span>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div class="inf_right">
            <div class="infR_top">
                <div class="itop_autor">热门作者</div>
                <div class="R_list">
                    <div class="i_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                    </div>
                    <div class="i_right">
                        <span>大姐头 <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/vip_icon.png') }}" class="vip_icon" /></span>
                        <p>大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师</p>
                    </div>
                </div>
                <div class="R_list">
                    <div class="i_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                    </div>
                    <div class="i_right">
                        <span>大姐头 <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/vip_icon.png') }}" class="vip_icon" /></span>
                        <p>大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师</p>
                    </div>
                </div>
                <div class="R_list">
                    <div class="i_left">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                    </div>
                    <div class="i_right">
                        <span>大姐头 <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/vip_icon.png') }}" class="vip_icon" /></span>
                        <p>大师大师大师大师大师大师大师大师大师大师大师大师大师大师大师</p>
                    </div>
                </div>
            </div>
            <div class="i_right_img"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></div>
            <div class="infR_top">
                <div class="itop_autor autor_border">近期热点</div>
                <ul class="infR_time">
                    <li><a href="javascript:;">本周</a></li>
                    <li><a href="javascript:;">当月</a></li>
                    <li><a href="javascript:;">季度</a></li>
                </ul>
                <ul class="new_list">
                    <li>
                        <span>1</span>
                        <a href="javascript:;">京东今年亏损了好多京东今年亏损了好多京东今年亏损了好多</a>
                    </li>
                    <li>
                        <span>2</span>
                        <a href="javascript:;">京东今年亏损了好多京东今年亏损了好多京东今年亏损了好多</a>
                    </li>
                    <li>
                        <span>3</span>
                        <a href="javascript:;">京东今年亏损了好多京东今年亏损了好多京东今年亏损了好多</a>
                    </li>
                    <li>
                        <span>4</span>
                        <a href="javascript:;">京东今年亏损了好多京东今年亏损了好多京东今年亏损了好多</a>
                    </li>
                    <li>
                        <span class="bg_ccc">5</span>
                        <a href="javascript:;">京东今年亏损了好多京东今年亏损了好多京东今年亏损了好多</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/main.js') }}"></script>
@endsection
<script type="text/javascript">
var option = {
    container: '#news-list',
    loadcount: '',
    loadmax: '',
    maxid: 0,
    loadlimit: '',
    cid: 1
};
setTimeout(function() {
    news.init(option);
}, 300);
</script>
