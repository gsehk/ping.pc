@if (!$reports->isEmpty())
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
            @foreach ($reports as $report)
                <tr>
                    <td>{{$report->created_at}}</td>
                    <td>{{$report->user->name}}</td>
                    <td><span class="s-fc3">{{$report->content or ''}}</span></td>
                    <td>
                        @if ($report->type == 'post')
                        @php $body = $report->resource->summary ?? '资源已被删除'; @endphp
                            帖子：{{str_limit($body, 12)}}
                        @else
                        @php $body = $report->resource->body ?? '资源已被删除'; @endphp
                            评论：{{str_limit($body, 12)}}
                        @endif
                    </td>
                    <td><a class="s-fc" href="{{ route('pc:reportdetail',['group_id'=>$group_id]) }}">详情</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif