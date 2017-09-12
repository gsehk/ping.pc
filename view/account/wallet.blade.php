@section('title')
    我的钱包
@endsection

@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
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
                        <span class="switch active" type="1">我的钱包</span>
                        <span class="zy_select t_c gap12">
                            <span>交易明细</span>
                            <ul>
                                <li data-value="1">全部</li>
                                <li data-value="2">收入</li>
                                <li data-value="3">支出</li>
                            </ul>
                            <i></i>
                        </span>
                        <span class="switch" type="3">提现记录</span>
                    </div>
                    <div class="wallet-body" id="wallet-info">
                        <div class="wallet-info clearfix">
                            <div class="remaining-sum">{{ $TS['wallet']['balance'] }}</div>
                            <div class="operate">
                                <button><a href="{{ route('pc:walletpay') }}">充值</a></button>
                                <button>提现</button>
                            </div>
                            <p class="gcolor">账户余额（元）</p>
                        </div>
                        <p>使用规则</p>
                        {{ $wallet['rule'] }}
                    </div>

                    <div class="wallet-body" id="wallet-records">
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
    $(function(){
        var type = {{ $type }};

        // 点击切换分类
        $('.perfect_title .switch').click(function(){
            switchType($(this).attr('type'));
            $(this).parent().find('.switch').removeClass('active');
            $(this).addClass('active');
        })

        $('.zy_select li').click(function(){
            var cate = $(this).data('value');
            switchType(2, cate);
        })

        switchType(type);
    })

    // 切换类型加载数据
    var switchType = function(type, cate){
        cate = cate || 0;
        $('.loading').hide();
        if (type == 1) { // 我的钱包
            $('#wallet-records').html('').hide();
            $('#wallet-info').show();
        } else {
            $('#wallet-info').hide();
            $('#wallet-records').html('').show();

            var params = {
                type: type
            }
            if (cate != 0) params.cate = cate;
            setTimeout(function() {
                scroll.init({
                    container: '#wallet-records',
                    loading: '#wallet-records',
                    url: '/account/wallet/records',
                    params: params
                });
            }, 300);
        }
    }
</script>
@endsection