@if ($record->isEmpty())
    <table class="m-table m-table-row">
        @if ($loadcount == 1)
        <thead>
            <tr>
                <th>交易时间</th>
                <th>交易名称</th>
                <th>交易金额</th>
                <th>操作</th>
            </tr>
        </thead>
        @endif
        <tbody>
            {{-- @foreach ($record as $item)
                <tr>
                    <td>{{$item->creared_at}}</td>
                    <td>{{$item->title}}</td>
                    <td><span class="s-fc3">+3.00</span></td>
                    <td><a class="s-fc" href="">详情</a></td>
                </tr>
            @endforeach --}}
            <tr>
                <td>2017-12-11 10:00</td>
                <td>申请帖子置顶</td>
                <td><span class="s-fc3">+3.00</span></td>
                <td><a class="s-fc" href="">详情</a></td>
            </tr>
        </tbody>
    </table>
@endif