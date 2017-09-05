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
        {{-- 钱包使用规则 --}}
        <div class="account_tab">
            <div class="perfect_title">
                <span><a href="{{ route('pc:wallet') }}">我的钱包</a></span>
                <span data-value="" class="zy_select t_c gap12">
                    <span><a href="{{ route('pc:trades') }}">交易明细</a></span>
                    <ul>
                        <li data-value="0"><a href="{{ route('pc:trades', 2) }}">全部</a></li>
                        <li data-value="1"><a href="{{ route('pc:trades', 1) }}">收入</a></li>
                        <li data-value="2"><a href="{{ route('pc:trades', 0) }}">支出</a></li>
                    </ul>
                    <i></i>
                </span>
                <span><a href="{{ route('pc:withdraw') }}">提现记录</a></span>
                <span class="wallet_role fr"><a class="active" href="{{ route('pc:withrule') }}">使用规则</a></span>
            </div>
            <div class="wallet-body">
                {{ $wallet['rule'] }}
            </div>
        </div>
        {{-- /钱包使用规则 --}}
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
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