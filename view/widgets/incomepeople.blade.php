<div class="income-rank">
    <div class="title">收入达人排行榜</div>
    <ul class="income-list">

        @if(!$incomes->isEmpty())
            @foreach($incomes as $income)
                <li>
                    <div class="fans-span">{{$loop->iteration}}</div>
                    <div class="income-avatar">
                        <img src="{{$income['avatar'] or $routes['resource'].'/images/avatar.png'}}" alt="{{$income['name']}}">
                    </div>
                    <div class="income-name">
                        <a class="name" href="">{{$income['name']}}</a>
                        <div class="answers-count">回答数：{{$income['extra']['answers_count']}}</div>
                    </div>
                </li>
            @endforeach
        @else
            <div class="no-groups">暂无相关信息</div>
        @endif
    </ul>
</div>
