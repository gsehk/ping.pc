<div class="hot-issues">
    <div class="title">热门问题</div>
    <ul class="hot-issues-list">
        @if(!$issues->isEmpty())
            @foreach($issues as $issue)
                <li>
                    <div class="rank-num">
                        <span @if($loop->index <= 2) class="blue" @elseif($loop->index >= 2) class="grey" @endif>{{$loop->iteration}}
                        </span>
                    </div>
                    <div class="hot-subject">
                        <a class="hot-issues-title" href="{{ Route('pc:groupread', $issue->id) }}">{{$issue->subject}}</a>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-issues">暂无相关信息</div>
        @endif
    </ul>
</div>