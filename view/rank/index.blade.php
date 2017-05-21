@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg fans_bg">
  <div class="dy_cont list_bg">
    <ul class="list_ul">
      <li><a href="{{ route('pc:rank',['type'=>1]) }}" class="fs-16 @if($type == 1) a_border @endif">用户排行榜</a></li>
      <li><a href="{{ route('pc:rank',['type'=>2]) }}" class="fs-16 @if($type == 2) a_border @endif">好友排行榜</a></li>
    </ul>
    <div class="fans_div">
      <div class="list_fans fans1">
        <div class="rank-tit"> 
          <span>粉丝排行榜</span>
          <span class="right">
            <i class="arrow-rank-l" id="followerlast" onclick="gorank(1,'follower',this,{{ $follower['ranknum'] }})"></i>
              <font id="followernum">{{$follower['firstrank']}}</font>/{{$follower['ranknum']}}
            <i class="arrow-rank-r" id="followernext" onclick="gorank(2,'follower',this,{{ $follower['ranknum'] }})"></i>
          </span>
        </div>
        <div class="list_pm fs-14">
          <span class="pm_1">排名</span>
          <span class="pm_2">昵称</span>
          <span class="pm_3">粉丝数</span>
        </div>
        <ul class="fans_ul">
            @if(isset($follower))
            @foreach($follower['list'] as $followerk=>$followerv)
            <div rel="followerdiv" @if($followerk > 1) style="display:none;" @else current="1" @endif>
                @foreach($followerv as $fv)
                <li>
                    <div class="fans_span1"><span>{{$fv->rank}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <img src="{{ $routes['resource'] }}/images/cicle.png" class="fans_img" />
                        {{$fv->name}}
                    </div>
                    <div class="fans_span3">{{$fv->value}}</div>
                </li>
                @endforeach
            </div>
            @endforeach
            @endif
        </ul>
        <div class="fans_ranking">您在全站粉丝排行榜中排第<span>{{$follower['userrank']}}</span>名</div>
      </div>
      <div class="list_fans">
        <div class="rank-tit"> 
            <span>积分排行榜</span>
            <span class="right">
                <i class="arrow-rank-l" id="creditlast" onclick="gorank(1,'credit',this,{{$credit['ranknum']}})"></i>
                    <font id="creditnum">{{$credit['firstrank']}}</font>/{{$credit['ranknum']}}
                <i class="arrow-rank-r" id="creditnext" onclick="gorank(2,'credit',this,{{$credit['ranknum']}})"></i>
            </span>
        </div>
        <div class="list_pm fs-14">
            <span class="pm_1">排名</span>
            <span class="pm_2">昵称</span>
            <span class="pm_3">积分数</span>
        </div>
        <ul class="fans_ul">
            @if(isset($credit))
            @foreach($credit['list'] as $creditk=>$creditv)
            <div rel="creditdiv" @if($creditk > 1) style="display:none;" @else current="1" @endif>
                @foreach($creditv as $cv)
                <li>
                    <div class="fans_span1"><span>{{$cv->rank}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <img src="{{ $routes['resource'] }}/images/cicle.png" class="fans_img" />
                        {{$cv->name}}
                    </div>
                    <div class="fans_span3">{{$cv->value}}</div>
                </li>
                @endforeach
            </div>
            @endforeach
            @endif
        </ul>
        <div class="fans_ranking">您在全站积分排行榜中排第<span>{{$credit['userrank']}}</span>名</div>
      </div>
    </div>
    <div class="fans_div">
      <div class="list_fans fans1">
        <div class="rank-tit"> 
          <span>内容发布排行榜</span>
          <span class="right">
            <i class="arrow-rank-l" id="postlast" onclick="gorank(1,'post',this,{{$post['ranknum']}})"></i>
              <font id="postnum">{{$follower['firstrank']}}</font>/{{$follower['ranknum']}}
            <i class="arrow-rank-r" id="postnext" onclick="gorank(2,'post',this,{{$post['ranknum']}})"></i>
          </span>
        </div>
        <div class="list_pm fs-14">
          <span class="pm_1">排名</span>
          <span class="pm_2">昵称</span>
          <span class="pm_3">分享发布数</span>
        </div>
        <ul class="fans_ul">
            @if(isset($post))
            @foreach($post['list'] as $postk=>$postv)
            <div rel="postdiv" @if($postk > 1) style="display:none;" @else current="1" @endif>
                @foreach($postv as $pv)
                <li>
                    <div class="fans_span1"><span>{{$pv->rank}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <img src="{{ $routes['resource'] }}/images/cicle.png" class="fans_img" />
                        {{$pv->user->name or ''}}
                    </div>
                    <div class="fans_span3">{{$pv->total}}</div>
                </li>
                @endforeach
            </div>
            @endforeach
            @endif
        </ul>
        <div class="fans_ranking">您在全站内容发布排行榜中排第<span>{{$post['userrank']}}</span>名</div>
      </div>
      <div class="list_fans">
        <div class="rank-tit"> 
            <span>累计签到排行榜</span>
            <span class="right">
                <i class="arrow-rank-l" id="checktotallast" onclick="gorank(1,'checktotal',this,{{$checktotal['ranknum']}})"></i>
                    <font id="checktotalnum">{{$checktotal['firstrank']}}</font>/{{$checktotal['ranknum']}}
                <i class="arrow-rank-r" id="checktotalnext" onclick="gorank(2,'checktotal',this,{{$checktotal['ranknum']}})"></i>
            </span>
        </div>
        <div class="list_pm fs-14">
            <span class="pm_1">排名</span>
            <span class="pm_2">昵称</span>
            <span class="pm_3">累计签到数</span>
        </div>
        <ul class="fans_ul">
            @if(isset($checktotal))
            @foreach($checktotal['list'] as $checktotalk=>$checktotalv)
            <div rel="checktotaldiv" @if($checktotalk > 1) style="display:none;" @else current="1" @endif>
                @foreach($checktotalv as $tv)
                <li>
                    <div class="fans_span1"><span>{{$tv->rank}}</span></div>
                    <div class="fans_span2 txt-hide">
                        <img src="{{ $routes['resource'] }}/images/cicle.png" class="fans_img" />
                        {{$tv->user->name or ''}}
                    </div>
                    <div class="fans_span3">{{$tv->total}}</div>
                </li>
                @endforeach
            </div>
            @endforeach
            @endif
        </ul>
        <div class="fans_ranking">您在全站积分排行榜中排第<span>{{$checktotal['userrank']}}</span>名</div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
function gorank(action,type,obj,num){
  var current = $('div[rel="'+type+'div"][current="1"]');
  //当前页数
  var curnum = $('#'+type+'num').text();
  //向前
  if ( action == 1 ){
    curnum = parseInt(curnum) - 1;
    if ( curnum  >= 1 ){
      if ( curnum == 1 ){
        // $(obj).attr('class','arrow-rank-l');
      }
      // $('#'+type+'next').attr('class','arrow-rank-r');
      var last = $('div[rel="'+type+'div"][current="1"]').prev();
      if ( last != undefined ){
        $(last).attr('current',1);
        $(current).removeAttr('current');
        $(last).show();
        $(current).hide();
      }
      $('#'+type+'num').text(curnum);
    }
  } else {
    //向后翻页
    curnum = parseInt(curnum) + 1;
    if ( curnum <= num ){
      if ( curnum == num ){
        // $(obj).attr('class','arrow-rank-r1');
      }
      // $('#'+type+'last').attr('class','arrow-rank-l1');
      var next = $('div[rel="'+type+'div"][current="1"]').next();
      if ( next != undefined ){
        $(next).attr('current',1);
        $(current).removeAttr('current');
        $(current).hide();
        $(next).show();
      }
      $('#'+type+'num').text(curnum);
    }
  } 
}
</script>
@endsection
