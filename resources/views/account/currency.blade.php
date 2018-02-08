@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@section('title')
    我的积分
@endsection

@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/pc/css/account.css')}}"/>
@endsection

@section('content')

    <div class="account_container">
        <div class="account_wrap">

            {{-- 左侧导航 --}}
            @include('pcview::account.sidebar')

            <div class="account_r">
                <div class="account_c_a" id="J-warp">

                    <div class="account_tab">
                        <div class="perfect_title">
                            <span class="switch @if($type == 1) active @endif" type="1">我的积分</span>
                            <span class="switch @if($type == 2) active @endif" type="2">积分明细</span>
                            <span class="switch @if($type == 3) active @endif" type="3">充值记录</span>
                            <span class="switch @if($type == 4) active @endif" type="4">提取记录</span>
                            <span class="switch @if($type == 5) active @endif" type="5" style="float: right;">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-doubt"></use></svg>
                                积分规则
                            </span>
                        </div>
                        <div class="wallet-body" id="wallet-info">
                            <div class="currency-info clearfix">
                                <div class="remaining-sum">

                                    {{ $currency_sum }}</div>
                                <div class="operate">
                                    <a href="{{ route('pc:currencypay') }}"><button>充值积分</button></a>
                                    <a href="{{ route('pc:currencydraw') }}"><button class="gray">提取积分</button></a>
                                </div>
                                <p class="gcolor">当前积分</p>
                            </div>
                        </div>
                        <div class="wallet-body" id="wallet-records">
                        </div>
                    </div>
                </div>
                @if($type==1)
                <div class="currency_list">
                    <span class="read-list">近期积分使用记录</span>
                    @if(!$currency->isEmpty())
                        <table class=" table_list table tborder" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <td width="30%">使用时间</td>
                                <td width="40%">积分使用途径</td>
                                <td width="30%">积分数量</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($currency as $item)
                                <tr>
                                    <td>{{getTime($item->created_at, 0, 0)}}</td>
                                    <td><p class="ptext">{{ $item->title }}</p></td>
                                    <td><font color="#FF9400">{{ $item->amount}}积分</font></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function(){
            var type = {{ $type }};

            // 点击切换分类
            $('.perfect_title .switch').click(function(){
                switchType($(this).attr('type'));
                $(this).parents('.perfect_title').find('span').removeClass('active');
                $(this).addClass('active');
            })
            switchType(type);
        })

        // 切换类型加载数据
        var switchType = function(type){
            $('.loading').hide();
            $('.click_loading').hide();
            if (type == 1) { // 我的钱包
                $('#wallet-records').html('').hide();
                $('#wallet-info').show();
                $('.currency_list').show();
            } else {
                $('#wallet-info').hide();
                $('.currency_list').hide();
                $('#wallet-records').html('').show();

                var params = {
                    type: type,
                    new: 1
                }
                scroll.init({
                    container: '#wallet-records',
                    loading: '#wallet-records',
                    url: '/account/currency/record',
                    params: params
                });
            }
        };

        // 充值检测
        var checkWallet = function (obj) {
            if (TS.BOOT['wallet:recharge-type'] && $.inArray('alipay_pc_direct', TS.BOOT['wallet:recharge-type'])) {
                var url = $(obj).data('url');
                window.location.href = url;
            } else {
                noticebox('未配置支付环境', 0);
                return false;
            }
        };
    </script>
@endsection