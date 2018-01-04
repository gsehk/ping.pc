@if (!$record->isEmpty())
    <table class="m-table m-table-row">
        @if ($loadcount == 1)
        <thead>
            <tr>
                <th>交易时间</th>
                <th>交易名称</th>
                <th>交易金额</th>
                {{-- <th>操作</th> --}}
            </tr>
        </thead>
        @endif
        <tbody>
            @foreach ($record as $item)
                <tr>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->subject}}</td>
                    <td><span class="s-fc3">+{{$item->amount * $config['bootstrappers']['wallet:ratio']}}</span></td>
                    {{-- <td><a class="s-fc" href="{{ route('pc:bankrolldetail',['group_id'=>$group_id]) }}">详情</a></td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@endif