@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">
<div class="account_wrap">

{{-- 左侧导航 --}}
@include('pcview::account.sidebar')

<div class="account_r">
    <div class="account_c_c" id="J-warp">
        {{-- 提现记录 --}}
        <div class="account_tab">
            <div class="perfect_title">
                <span><a href="{{ route('pc:wallet') }}">我的钱包</a></span>
                <span data-value="" class="zy_select t_c gap12">
                    <span><a href="{{ route('pc:trades') }}">交易明细</a></span>
                    <ul>
                        <li data-value="0" class="active"><a href="{{ route('pc:trades', 2) }}">全部</a></li>
                        <li data-value="1"><a href="{{ route('pc:trades', 1) }}">收入</a></li>
                        <li data-value="2"><a href="{{ route('pc:trades', 0) }}">支出</a></li>
                    </ul>
                    <i></i>
                </span>
                <span><a class="active" href="{{ route('pc:withdraw') }}">提现记录</a></span>
                <span class="wallet_role fr"><a href="{{ route('pc:withrule') }}">使用规则</a></span>
            </div>
            <div class="wallet-body">
                <div class="wallet-table">
                    <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="20%">申请时间</th>
                                <th width="30%">备注</th>
                                <th width="10%">提现金额</th>
                                <th width="10%">状态</th>
                                {{-- <th width="15%">操作</th> --}}
                            </tr>
                        </thead>
                        <tbody id="ordere-list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- /提现记录 --}}
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
setTimeout(function() {
    scroll.init({
        container: '#ordere-list',
        loading: '.table',
        url: '/account/order',
        params: {limit: 10, cate: {{$type}} }
    });
}, 300);

$(function() {
    var select = $(".zy_select");

    select.on("click", function(e){
        e.stopPropagation();
        return !($(this).hasClass("open")) ? $(this).addClass("open") : $(this).removeClass("open");
    });

    select.on("click", "li", function(e){
        e.stopPropagation();
        var $this = $(this).parent("ul");
        $(this).addClass("active").siblings(".active").removeClass("active");
        $this.prev('span').html($(this).html());
        $this.parent(".zy_select").removeClass("open");
        $this.parent(".zy_select").data("value", $(this).data("value"));
    });

    $(document).click(function() {
        select.removeClass("open");
    });
});
</script>
@endsection