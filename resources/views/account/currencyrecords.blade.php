@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if ($type == 2)
    {{-- 积分明细 --}}
    @if(!$currency->isEmpty())
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="20%">使用时间</th>
                        <th width="30%">积分使用途径</th>
                        <th width="30%">状态</th>
                        <th width="20%">积分数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr>
                        <td width="20%">{{ getTime($item->created_at, 0, 0) }}</td>
                        <td width="30%"><p class="ptext">{{ $item->title }}</p></td>
                        <td width="30%"><p class="ptext">
                                @if ($item->state == 0) 等待 @endif
                                @if ($item->state == 1) 完成 @endif
                                @if ($item->state == -1) 失败 @endif</p></td>
                        <td width="20%">
                            <font color="#FF9400">{{ $item->amount}}积分</font>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@elseif ($type == 3)
    {{-- 充值记录 --}}
    @if(!$currency->isEmpty())
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="30%">使用时间</th>
                        <th width="40%">状态</th>
                        <th width="30%">积分数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr>
                        <td width="30%">{{ getTime($item->created_at, 0, 0) }}</td>
                        <td width="40%">
                            @if ($item->state == 0) 等待 @endif
                            @if ($item->state == 1) 完成 @endif
                            @if ($item->state == -1) 失败 @endif
                        </td>
                        <td width="30%"><p class="ptext"><font color="#FF9400">{{ $item->amount}}积分</font></p></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@elseif ($type == 4)
    {{-- 提取记录 --}}
    @if(!$currency->isEmpty())
        <div class="wallet-table">
            <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                @if ($loadcount == 1)
                    <thead>
                    <tr>
                        <th width="30%">使用时间</th>
                        <th width="40%">状态</th>
                        <th width="30%">积分数量</th>
                    </tr>
                    </thead>
                @endif
                <tbody>
                @foreach ($currency as $item)
                    <tr>
                        <td width="30%">{{ getTime($item->created_at, 0, 0) }}</td>
                        <td width="40%">
                            @if ($item->state == 0) 正在审核 @endif
                            @if ($item->state == 1) 提取成功 @endif
                            @if ($item->state == -1) 提取失败 @endif
                        </td>
                        <td width="30%"><p class="ptext"><font color="#FF9400">{{ $item->amount}}积分</font></p></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@else
    @if($currency)
        <div class="rules">
            {{$currency['rule']}}
        </div>
    @endif
@endif