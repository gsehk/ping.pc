@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg">
    <div class="dy_cont">
        <!--top-->
        <div class="dyn_top">
            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="dynTop_bg" />
            <span class="dyn_huan">更换封面</span>
            <div class="dyn_title">小鬼大王</div>
            <div class="dynTop_cont">一觉睡到小时候，没有什么比这更舒服和向往的事</div>
            <div class="dyn_lImg"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" /> </div>
        </div>
        <div class="dynTop_b">
            <span class="dyn_zy"><i class="icon iconfont icon-gongsi"></i>智艺创想</span>
            <span class="dyn_time"><i class="icon iconfont icon-shengri"></i>09.30<label>女</label></span>
            <span class="dyn_address"><i class="icon iconfont icon-site"></i>成都·高新区</span>
            <a href="{{Route('pc:newsrelease')}}" class="dyn_contribute"><i class="icon iconfont icon-feiji tougao"></i>投稿</a>
        </div>
        <div>
            <!--left-->
            <div class="dy_left"></div>
            <!--《center》-->
            <div class="dy_cCont dy_left_border">
                <div class="dy_center" style="width:664px;">
                    <div class="dy_cen">
                        <div style="position:relative;">
                            <div class="artic_left">
                                <a href="{{Route('pc:myFeed', ['type'=>'all'])}}" class="fs-16 @if($type == 'all') dy_cen_333 @endif">全部动态</a>
                                <a href="{{Route('pc:myFeed', ['type'=>'img'])}}" class="fs-16 @if($type == 'img') dy_cen_333 @endif">图片</a>
                                <a href="{{Route('pc:myFeed', ['type'=>'video'])}}" class="fs-16 @if($type == 'video') dy_cen_333 @endif">视频</a>
                                <a href="{{Route('pc:myFeed', ['type'=>'news'])}}" class="fs-16 @if($type == 'news') dy_cen_333 @endif">资讯</a>
                            </div>
                            <a href="{{Route('pc:article')}}"><div class="artic_artic fs-16">文章</div></a>
                        </div>
                        <div style="margin-top: 60px;">
                            <div class="dy_c">
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor ">
                                <span class="cen_beforColor">今<br />天</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive img1">
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
                                        121
                                    </span>
                                    <span class="cen_right">
                                        <i class="icon iconfont icon-gengduo-copy"></i>
                                    </span>
                                </div>
                                <div class="dy_line">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/line.png') }}">
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
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor b_bg">
                                <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
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
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor b_bg">
                                <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive img1">
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-white"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
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
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor b_bg">
                                <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <div class="dy_img4">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img4">
                                </div>
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
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
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor b_bg">
                                <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <div class="dy_img6">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_1">
                                    <div class="img6_div">
                                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive">
                                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img-responsive">
                                    </div>
                                </div>
                                <div class="dy_img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                </div>
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
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
                                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}">
                                <span class="dy_name fs-14">大师</span>
                                <span class="dy_time fs-12">5分钟前</span>
                            </div>
                            <div class="cen_img cen_befor b_bg">
                                <span class="cen_beforColor_two"><span class="beforColor_span">14</span>04</span>
                                <p class="fs-14 cen_word ">你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候你写ppt时候</p>
                                <div class="dy_img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                    <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" class="img6_bottom">
                                </div>
                                <div class="dy_comment">
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-xihuan-red"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use></svg>
                                        121
                                    </span>
                                    <span>
                                        <svg class="icon" aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chakan"></use></svg>
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
            <!--<right>-->
            <div class="dy_right">
                <div class="uc-feed-rightbox">
                    <ul class="infR_time">
                        <li><a href="javascript:;">粉丝</a></li>
                        <li><a href="javascript:;">关注</a></li>
                        <li><a href="{{Route('pc:myFans', ['type'=>3])}}">访客</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
