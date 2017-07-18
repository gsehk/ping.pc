@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_cont clearfix">
    <!--左-->
    @component('pcview::ucmenu')
        555
    @endcomponent

    <!-- 中 -->
    <div class="dy_cCont">
        <div class="dy_center">
            @if(!empty($TS))
            <div class="dy_cTop">
                <textarea class="dy_ta" placeholder="说说新鲜事" id="feed_content" onPropertyChange="alert('test')"></textarea>
                <div class="dy_company">
                    <!-- <span class="fs-14" id="feed_expression">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-biaoqing"></use></svg>
                        表情
                    </span> -->
                    <span class="fs-14" id="feed_pic">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-tupian"></use></svg>
                        图片
                    </span>
                    <a href="javascript:;" class="dy_share" onclick="weibo.postFeed()">分享</a>
                </div>
            </div>
            @endif
            <div class="dy_cen">
                <div class="show_tab">
                    @if (!empty($TS))
                    <a href="javascript:;" data-cid="1" class="fs-16 @if ($type == 1) dy_cen_333 @endif">关注的</a>
                    @endif
                    <a href="javascript:;" data-cid="2" class="fs-16 @if ($type == 2) dy_cen_333 @endif">热门</a>
                    <a href="javascript:;" data-cid="3" class="fs-16 @if ($type == 3) dy_cen_333 @endif">最新</a>
                </div>
                <div id="feeds-list"></div>
            </div>
        </div>
    </div>

    <!-- 右边 -->
    <div class="dy_right">
        @if (!empty($TS))
        <!-- 签到 -->
        <div class="dy_signed">
            <div class="dyrTop">
                <span class="dyrTop_r fs-14">
                    {{$TS['name']}}
                    {{-- <span class="totalnum">{{ $TS['credit'] or 0 }}</span> --}}
                </span>
                <a href="{{ route('pc:myFeed', ['user_id' => $TS['id']]) }}">
                <img src="{{ $TS['avatar'] }}" class="dyrTop_img" alt="{{ $TS['name'] }}"/>
                </a>
            </div>
            <div class="index_intro">{{$TS['intro']}}</div>
            {{-- @if(empty($ischeck))
                <div class="dy_qiandao" onclick="checkin();" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>每日签到<span>+5积分</span></div>
            @else 
                <div class="dy_qiandao" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">{{$checkin['con_num']}}</font>天</span></div>
            @endif --}}
        </div>
        @endif

        <!-- 推荐用户 -->
        @if (!empty($rec_users))
        <div class="dyrBottom">
            <ul>
                @foreach ($rec_users as $rec_user)
                <li>
                    <a href="{{ route('pc:myFeed', ['user_id' => $rec_user['id']]) }}">
                    <img src="{{ $rec_user['avatar'] }}" alt="{{ $rec_user['name'] }}"/>
                    </a>
                    <span><a href="{{ route('pc:myFeed', ['user_id' => $rec_user['id']]) }}">{{ $rec_user['name'] }}</a></span>
                </li>
                @endforeach
            </ul>
            <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多推荐用户</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/js/jquery.uploadify.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script type="text/javascript">

var checkin = function(){
  if( MID == 0 ){
    return;
  }
  var totalnum = {{$checkin['total_num'] or 0}} + 1;
  $.get('/home/checkin' , {} , function (res){
    if ( res ){
      var totalnum = res.data.score;
      $('#checkin').html('<svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">'+res.data.con_num+'</font>天</span>');
      $('.totalnum').text(totalnum);
    }
  });
};

// 加载微博
setTimeout(function() {
    weibo.init({
        container: '#feeds-list',
        loading: '.dy_cen',
        cid: "{{$type}}"
    });
}, 300);

$(function(){
    // 发布微博
    var loadgif = PUBLIC_URL + '/images/loading.png';
    var up = $('.dy_company').Huploadify({
        auto:true,
        multi:true,
        newUpload:true,
        buttonText:''
    });
});
</script>
@endsection
