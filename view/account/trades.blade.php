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
        {{-- 交易明细 --}}
        <div class="account_tab">
            <div class="perfect_title">
                <span><a href="{{ route('pc:wallet') }}">我的钱包</a></span>
                <span data-value="" class="zy_select t_c gap12">
                    <span class="active">
                        @if ($type == 0)
                            支出
                        @elseif($type == 1)
                            收入
                        @else
                            交易明细
                        @endif
                    </span>
                    <ul class="J-wallet-type">
                        <li data-value="2">全部</li>
                        <li data-value="1">收入</li>
                        <li data-value="0">支出</li>
                    </ul>
                    <i></i>
                </span>
                <span><a href="{{ route('pc:withdraw') }}">提现记录</a></span>
                <span class="wallet_role fr"><a href="{{ route('pc:withrule') }}">使用规则</a></span>
            </div>
            <div class="wallet-body">
                <div class="wallet-table">
                    <table class="table tborder" border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="25%">交易时间</th>
                                <th width="30%">交易名称</th>
                                <th width="30%">交易金额</th>
                                <th width="15%">操作</th>
                            </tr>
                        </thead>
                        <tbody id="ordere-list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- /交易明细 --}}
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
        params: {limit: 10, type: {{$type}} }
    });
}, 300);

$('.J-wallet-type > li').on('click', function(){
    var type = $(this).data('value');
    $('#ordere-list').html('');
    scroll.init({
        container: '#ordere-list',
        loading: '.table',
        url: '/account/order',
        params: {limit: 10, type: type }
    });
});

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