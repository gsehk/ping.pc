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
                <div class="show_tab">
                    <a href="javascript:;" data-cid="1" class="fs-16 @if ($type == 1) dy_cen_333 @endif">关注的</a>
                    <a href="javascript:;" data-cid="2" class="fs-16 @if ($type == 2) dy_cen_333 @endif">热门</a>
                    <a href="javascript:;" data-cid="3" class="fs-16 @if ($type == 3) dy_cen_333 @endif">最新</a>
                </div>
                <div id="feeds-list">
                    
                </div>
                <!-- <div>
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
                </div> -->
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

@section('scripts')
<script src="{{\Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/module.weibo.js')}}"></script>
<script type="text/javascript">
var option = {
    container: '#feeds-list',
    loadcount: '',
    loadmax: '',
    maxid: 0,
    loadlimit: '',
    cid: "{{$type}}"
};
setTimeout(function() {
    weibo.init(option);
}, 300);

$(function(){
    $('.show_tab a').on('click', function(){
        var cid = $(this).data('cid');
        var option = {
            container: '#feeds-list',
            cid: cid
        };
        $(option.container).html('');
        weibo.init(option);
        $('.show_tab a').removeClass('dy_cen_333');
        $(this).addClass('dy_cen_333');
    });
    $('#feeds-list').on('click', '.show_admin', function(){
        if ($(this).next('.cen_more').css('display') == 'none') {
            $(this).next('.cen_more').show();
        } else {
            $(this).next('.cen_more').hide();
        }
    });
});
</script>
@endsection
