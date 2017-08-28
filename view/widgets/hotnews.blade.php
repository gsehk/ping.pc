<div class="hot_news">
    <div class="title">近期热点</div>
    <ul class="time_menu" id="time_menu">
        <li class="time"><a href="javascript:;" cid="1" class="week a_border">一周</a></li>
        <li class="time"><a href="javascript:;" cid="2" class="meth">月度</a></li>
        <li class="time"><a href="javascript:;" cid="3" class="moth">季度</a></li>
    </ul>
    <ul class="hot_news_list"">
        <div>
            @if(!$week->isEmpty())
            @foreach($week as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
        <div class="hide">
            @if(!$month->isEmpty())
            @foreach($hots['month'] as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
        <div class="hide">
            @if(!$quarter->isEmpty())
            @foreach($quarter as $item)
                <li>
                    <span @if($loop->index > 2) class="grey" @endif>{{$loop->iteration}}</span>
                    <a href="{{ Route('pc:newsRead', $item->id) }}">{{$item->title}}</a>
                </li>
            @endforeach
            @else
                <div class="loading">暂无相关信息</div>
            @endif
        </div>
    </ul>
</div>