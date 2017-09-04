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
        {{-- 我的钱包 --}}
        <div class="account_tab">
            <div class="perfect_title">
                <div class="active">我的钱包</div>
                <div data-value="" class="zy_select t_c gap12">
                    <span>交易明细</span>
                    <ul>
                        <li data-value="0" class="active">全部</li>
                        <li data-value="1">收入</a></li>
                        <li data-value="2">支出</a></li>
                    </ul>
                    <i></i>
                </div>
                <div>充值记录</div>
                <div>提现记录</div>

                <div class="wallet_role">
                    <span>使用规则</span>
                </div>
            </div>
            <div class="wallet-body">
                <div class="wallet-info clearfix">
                    <div class="remaining-sum">67800.00</div>
                    <div class="operate">
                        <button>充值</button>
                        <button>提现</button>
                    </div>
                    <p class="ucolor">账户余额（元）</p>
                </div>
                <div class="wallet-table">
                    <p>近期交易记录</p>
                    <table class="table">
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
        {{-- /我的钱包 --}}
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
        params: {limit: 10}
    });
}, 300);
</script>
@endsection