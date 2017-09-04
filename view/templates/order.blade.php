@foreach ($order as $item)
    <tr>
        <td width="25%">{{ $item->created_at }}</td>
        <td width="30%">{{ $item->subject }}</td>
        <td width="30%">
            <font color="#FF9400">{{ $item->action==1 ? '+'.$item->amount/100 : '-'.$item->amount/100 }}</font>
        </td>
        <td width="15%"><a class="mcolor J-order-detail" oid="{{ $item->id }}" href="javascript:;">详情</a></td>
    </tr>
@endforeach
