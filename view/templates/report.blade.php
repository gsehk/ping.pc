@if ($reports->isEmpty())
    <table class="m-table m-table-row">
        <thead>
            <tr>
                <th>举报时间</th>
                <th>举报人</th>
                <th>举报内容</th>
                <th>举报来源</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($reports as $report)
                <tr>
                    <td>2017-12-11 10:00</td>
                    <td>申请帖子置顶</td>
                    <td><span class="s-fc3">+3.00</span></td>
                    <td>评论；"啊啊啊"</td>
                    <td><a class="s-fc" href="{{ route('pc:reportdetail') }}">详情</a></td>
                </tr>
            @endforeach --}}
        </tbody>
    </table>
@endif