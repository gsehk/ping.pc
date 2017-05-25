@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg">
    <div class="dy_cont">
        <!--左-->
        @component('pcview::ucmenu')
        @endcomponent
        <!--中-->
        <div class="dy_cCont">
            <div class="dy_center">
                <div class="dy_cen">
                    <div class="collection_memu">
                        <a href="javascript:;" data-type="feed" class="fs-16 dy_cen_333">动态</a>
                        <a href="javascript:;" data-type="news" class="fs-16">文章</a>
                    </div>
                    <div id="content-list">
                    </div>
                </div>
            </div>
        </div>
        <!--右-->
        <div class="dy_right">
            @if (!empty($TS))
            <!-- 签到 -->
            <div class="dy_signed">
                <div class="dyrTop">
                    <span class="dyrTop_r fs-14">
                        已获积分
                        <span class="totalnum">{{ $TS['credit'] or 0 }}</span>
                    </span>
                    @if (!empty($TS['avatar']))
                    <img src="{{ $routes['storage'] }}{{ $TS['avatar']}} " class="dyrTop_img" alt="{{ $TS['name'] }}"/>
                    @else
                    <img src="{{ $routes['resource'] }}/images/avatar.png" class="dyrTop_img"/>
                    @endif
                </div>
                @if(empty($ischeck))
                    <div class="dy_qiandao" onclick="checkin();" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>每日签到<span>+5积分</span></div>
                @else 
                    <div class="dy_qiandao" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">{{$checkin['con_num']}}</font>天</span></div>
                @endif
            </div>
            @endif

            <!-- 推荐用户 -->
            @if (!empty($rec_users))
            <div class="dyrBottom">
                <ul>
                    @foreach ($rec_users as $rec_user)
                    <li>
                        <a href="{{ route('pc:myFeed', ['user_id' => $rec_user['id']]) }}">
                        @if (!empty($rec_user['avatar']))
                        <img src="{{ $routes['storage'] }}{{ $rec_user['avatar'] }}" />
                        @else
                        <img src="{{ $routes['resource'] }}/images/avatar.png"/>
                        @endif
                        </a>
                        <span><a href="{{ route('pc:myFeed', ['user_id' => $rec_user['id']]) }}">{{ $rec_user['name'] }}</a></span>
                    </li>
                    @endforeach
                </ul>
                <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多推荐用户</a>
            </div>
            @endif
            <!-- 个人中心右侧推荐用户 -->

        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{ $routes['resource'] }}/js/module.profile.js"></script>
<script type="text/javascript">
var checkin = function(){
  if( MID == 0 ){
    return;
  }
  var totalnum = {{$checkin['total_num'] or 0}} + 1;
  var connum = {{$checkin['con_num'] or 0}} + 1;
  $.get('/home/checkin' , {} , function (res){
    if ( res ){
      var totalnum = res.data.score;
      $('#checkin').html('<svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">'+connum+'</font>天</span>');
      $('.totalnum').text(totalnum);
    }
  });
};

setTimeout(function() {
    news.init({
        container: '#content-list',
        user_id:"{{$TS['id']}}",
        type:"feed"
    });
}, 300);
// 文章分类tab
$('.collection_memu a').on('click', function(){
    var type = $(this).data('type');
    $('#content-list').html('');
    news.init({container: '#content-list',user_id:"{{$TS['id']}}",type:type});
    $('.collection_memu a').removeClass('dy_cen_333');
    $(this).addClass('dy_cen_333');
});

</script>
@endsection