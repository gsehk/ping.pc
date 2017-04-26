@extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg">
    <div class="dy_cont">
        <!--左-->
        @component('ucmenu')
            555
        @endcomponent
    </div>
    <div class="dy_cCont">
        <div class="dy_center">
            <div class="dy_cTop">
                <textarea class="dy_ta" placeholder="说说新鲜事"></textarea>
                <div class="dy_company">
                    <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span>
                    <span class="fs-14">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                        图片
                    </span>
                    <a href="javascript:;" class="dy_share">分享</a>
                </div>
                <div class="dy_picture">
                    <a href="javascript:;" class="dy_picture_span">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="dy_picture_span_img" />
                        <span>
                            <i class="icon iconfont icon-close"></i>
                        </span>
                    </a>
                </div>
            </div>
            <div class="dy_cen">
                <div>
                    <a href="{{Route('pc:feedAll', ['type'=>1])}}" class="fs-16 @if ($type == 1) dy_cen_333 @endif">关注的</a>
                    <a href="{{Route('pc:feedAll', ['type'=>2])}}" class="fs-16 @if ($type == 2) dy_cen_333 @endif">热门</a>
                    <a href="{{Route('pc:feedAll', ['type'=>3])}}" class="fs-16 @if ($type == 3) dy_cen_333 @endif">最新</a>
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
                        <div class="dy_img4">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4" />
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4" />
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4" />
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4" />
                        </div>
                        <div class="dy_comment">
                            <span>
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
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
    <div class="dy_right">
        @component('signed')
            555
        @endcomponent

        @component('related')
            {{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}
        @endcomponent
    </div>
</div>
@endsection
