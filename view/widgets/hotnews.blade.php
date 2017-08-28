<div class="infR_top">
    <div class="itop_autor autor_border">近期热点</div>
    <ul class="infR_time infR_time_1" id="j-recent-hot">
        <li class="infR_time_3"><a href="javascript:;" cid="1" class="week a_border">一周</a></li>
        <li class="infR_time_3"><a href="javascript:;" cid="2" class="meth">月度</a></li>
        <li class="infR_time_3"><a href="javascript:;" cid="3" class="moth">季度</a></li>
    </ul>
    <ul class="new_list" id="j-recent-hot-wrapp">
        <div class="list list1">
            @if(!$hots['week']->isEmpty())
            @foreach($hots['week'] as $week)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $week->id) }}">{{$week->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
        <div class="list list2">
            @if(!$hots['month']->isEmpty())
            @foreach($hots['month'] as $month)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $month->id) }}">{{$month->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
        <div class="list list3">
            @if(!$hots['quarter']->isEmpty())
            @foreach($hots['quarter'] as $quarter)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $quarter->id) }}">{{$quarter->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
    </ul>
</div>