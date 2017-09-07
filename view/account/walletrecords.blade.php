@if ($type == 2 || $type == 3) 
    {{-- 交易记录 --}}
    @if(!$records->isEmpty())
    <div class="wallet-table">
        <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="25%">交易时间</th>
                    <th width="30%">交易名称</th>
                    <th width="30%">交易金额</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $item)
                <tr>
                    <td width="25%">{{ $item->created_at }}</td>
                    <td width="30%">{{ $item->subject }}</td>
                    <td width="30%">
                        <font color="#FF9400">{{ $item->action==1 ? '+'.$item->amount/100 : '-'.$item->amount/100 }}</font>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@else
    {{-- 提现记录 --}}
    @if(!$records->isEmpty())
    <div class="wallet-table">
        <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="20%">申请时间</th>
                    <th width="30%">备注</th>
                    <th width="10%">提现金额</th>
                    <th width="10%">状态</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $item)
                <tr>
                    <td width="20%">{{ $item->created_at }}</td>
                    <td width="30%">{{ $item->remark }}</td>
                    <td width="10%"><font color="#FF9400">{{ $item->value/100 }}</font></td>
                    <td width="10%">
                        @if ($item->status == 0) 待审批 @endif
                        @if ($item->status == 1) 已审批 @endif
                        @if ($item->status == 2) 拒绝 @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endif