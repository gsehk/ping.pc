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
    <div class="account_c_c">
        {{-- 我的钱包 --}}
        <div class="account_tab" id="J-input">
            <div class="perfect_title">
                <div class="active">我的钱包</div>

                <!-- zy_select -->
                <!-- 
                     data-value 为当前选择项的 data-value 值 

                     2套ui：
                         .gap12 弹窗式（不带边框）
                         .border 美化后原生ui

                     .t_c 文字居中显示
                     .open 激活select选择框

                     js 写在 common.js 最后
                 -->
                <div data-value="" class="zy_select t_c gap12">
                    <!-- value -->
                    <span>交易明细</span>
                    <!-- /value -->
                    <!-- options -->
                    <ul>
                        <li data-value="0" class="active">全部</li>
                        <li data-value="1">收入</a></li>
                        <li data-value="2">支出</a></li>
                    </ul>
                    <!-- /options -->
                    <!-- icon -->
                    <i></i>
                    <!-- /icon -->
                </div>
                <!-- /zy_select -->

                <div>充值记录</div>
                <div>提现记录</div>

                <!-- role -->
                <div class="wallet_role">
                    <span>使用规则</span>
                </div>
                <!-- /role -->
            </div>
            <div class="account_table">

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
$('#J-user-security').on('click', function(){
	var getArgs = function() {
        var inp = $('#J-input input').toArray();
        var sel;
        for (var i in inp) {
            sel = $(inp[i]);
            args.set(sel.attr('name'), sel.val());
        };
        return args.get();
    };
    $.ajax({
        url: '/api/v2/user/password',
        type: 'PUT',
        data: getArgs(),
        dataType: 'json',
        error: function(xml) {
            noticebox('密码修改失败', 0);
        },
        success: function(res) {
            noticebox('密码修改成功', 1, 'refresh');
        }
    });
});
</script>
@endsection