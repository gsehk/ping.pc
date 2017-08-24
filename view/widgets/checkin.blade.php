@if (!empty($TS))
<div class="dy_signed">
    <div class="dyrTop">
        <span class="dyrTop_r fs-14">
            {{$TS['name']}}
            {{-- <span class="totalnum">{{ $TS['credit'] or 0 }}</span> --}}
        </span>
        <a href="{{ route('pc:mine', $TS['id']) }}">
        <img src="{{ $TS['avatar'] or $routes['resource'] . '/images/avatar.png' }}" class="dyrTop_img" alt="{{ $TS['name'] }}"/>
        </a>
    </div>
    <div class="index_intro">{{ $TS['bio'] or '' }}</div>
    {{-- @if(empty($ischeck))
        <div class="dy_qiandao" onclick="checkin();" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>每日签到<span>+5积分</span></div>
    @else 
        <div class="dy_qiandao" id="checkin"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-qiandao1"></use></svg>已签到<span>连续签到<font class="colnum">{{$checkin['con_num']}}</font>天</span></div>
    @endif --}}
</div>
@endif