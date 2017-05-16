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
                <textarea class="dy_ta" placeholder="说说新鲜事" id="feed_content" onPropertyChange="alert('test')"></textarea>
                <div class="dy_company">
                    <span class="fs-14" id="feed_expression">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span>
                    <span class="fs-14" id="feed_pic">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                        图片
                    </span>
                    <a href="javascript:;" class="dy_share dy_share_feed" onclick="weibo.postFeed()">分享</a>
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
            </div>
        </div>
    </div>
    <div class="dy_right">
        @if (!empty($TS))
        <!-- 个人中心右边签到 -->
        <div class="dy_signed">
            <div class="dyrTop">
                <span class="dyrTop_r fs-14">
                    已获积分
                    <span class="totalnum">{{ $TS['credit'] or 0 }}</span>
                </span>
                @if (!empty($TS['avatar']))
                <img src="{{ $routes['storage'] }}{{ $TS['avatar']}} " class="dyrTop_img" alt="{{ $TS['name'] }}"/>
                @else
                <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/avatar.png') }}" class="dyrTop_img" />
                @endif
            </div>
            @if(empty($ischeck))
                <div class="dy_qiandao" onclick="checkin();" id="checkin">每日签到<span>+5积分</span></div>
            @else 
                <div class="dy_qiandao dy_qiandao_sign" id="checkin">已签到</div>
            @endif
        </div>
        @endif


        @component('related')
            {{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}
        @endcomponent
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/module.weibo.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/jquery.uploadify.js') }}"></script>
<script src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('js/md5-min.js') }}"></script>
<script type="text/javascript">

var checkin = function(){
  if( MID == 0 ){
    return;
  }
  var totalnum = {{$checkin['total_num'] or 0}} + 1;
  var credit_score = {{$credit['score']}};
  $.get('/home/checkin' , {} , function (res){
    if ( res ){
      var totalnum = res.data.score;
      $('#checkin').html('已签到');
      $('.totalnum').text(totalnum);
      $('#checkin').addClass('dy_qiandao_sign');
    }
  });
};

$(function(){
    // 发布微博
    var loadgif = PUBLIC_URL + '/images/loading.png';
    var up = $('.dy_company').Huploadify({
        fileTypeExts: '*.jpg,*.png',
        auto:true,
        multi:true,
        newUpload:true,
        buttonText:''
    });

    //为删除文件按钮绑定删除文件事件
    $(".dy_cTop").on("click", ".imgdel", function(){
        $(this).parent().remove();
　　});

})

// 加载微博
setTimeout(function() {
    weibo.init({
        container: '#feeds-list',
        cid: "{{$type}}"
    });
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
