@extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg">
    <div class="dy_cont">
        <!--左-->
        @component('ucmenu')
            555
        @endcomponent
        <!--中-->
        <div class="dy_cCont">
            <div class="dy_center">
                <div class="dy_cen" style="border-top:0;">
                    <div>
                        <a href="{{Route('pc:collection', ['type'=>1])}}" class="fs-16 @if($type == 1) dy_cen_333 @endif">动态</a>
                        <a href="{{Route('pc:collection', ['type'=>2])}}" class="fs-16 @if($type == 2) dy_cen_333 @endif">文章</a>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img">
                            <p class="fs-14 cen_word">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive img1" />
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                                <div class="cen_more">
                                    <ul>
                                        <li><a href="#"><i class="icon iconfont icon-shoucang-copy"></i>收藏</a></li>
                                        <li><a href="#"><i class="icon iconfont icon-jubao-copy1"></i>举报</a></li>
                                        <li><a href="#"><i class="icon iconfont icon-shanchu-copy1"></i>删除</a></li>
                                        <li><a href="#"><i class="icon iconfont icon-zhiding-copy-copy1"></i>置顶</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="dy_line">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/line.png') }}" />
                            </div>
                            <div class="dy_comit">
                                <p>
                                    <span>Ellen：</span> 第一条评论
                                    <a class="fs-14">回复</a>
                                </p>
                                <p>
                                    <span>Nick </span>回复<span>Ellen：</span>
                                    第二条评论
                                </p>
                                <p>
                                    <span>Woody：</span> 回复第一条评论
                                </p>
                                <div class="comit_all fs-12">查看全部评论</div>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img">
                            <p class="fs-14 cen_word">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                    <div>
                        <div class="dy_c">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" />
                            <span class="dy_name fs-14">大师</span>
                            <span class="dy_time fs-12">5分钟前</span>
                        </div>
                        <div class="cen_img">
                            <p class="fs-14 cen_word">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                            <div class="dy_comment">
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                    121
                                </span>
                                <span>
                                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chakan"></use></svg>
                                    121
                                </span>
                                <span class="cen_right">
                                    <i class="icon iconfont icon-gengduo-copy"></i>
                                </span>
                            </div>
                            <div class="f3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--右-->
        <div class="dy_right">
            @component('signed')
                555
            @endcomponent

            @component('related')
                {{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}
            @endcomponent
        </div>
    </div>

    <!--加载中-->
    <div class="dy_loading">
        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/loading.png') }}" />
        加载中
    </div>
</div>
@endsection
