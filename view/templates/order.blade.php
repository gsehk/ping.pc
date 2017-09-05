{{-- 交易记录 --}}
@if ($type == 1)

@foreach ($order as $item)
    <tr>
        <td width="25%">{{ $item->created_at }}</td>
        <td width="30%">{{ $item->subject }}</td>
        <td width="30%">
            <font color="#FF9400">{{ $item->action==1 ? '+'.$item->amount/100 : '-'.$item->amount/100 }}</font>
        </td>
        <td width="15%"><a class="act" href="{{ route('pc:order', $item->id) }}">详情</a></td>
    </tr>
@endforeach

{{-- 充值记录 --}}
@elseif($type == 2)

@foreach ($order as $item)
    <tr>
        <td width="20%">{{ $item->created_at }}</td>
        <td width="30%">{{ $item->remark }}</td>
        <td width="10%"><font color="#FF9400">{{ $item->value/100 }}</font></td>
        <td width="10%">
            @if ($item->status == 0) 待审批 @endif
            @if ($item->status == 1) 已审批 @endif
            @if ($item->status == 2) 拒绝 @endif
        </td>
        <td width="15%"><a class="act" href="#">详情</a></td>
    </tr>
@endforeach

{{-- 提现记录 --}}
@elseif($type == 3)

@endif