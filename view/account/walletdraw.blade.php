@section('title') 提现 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="pay-box">
    <h1 class="title">提现</h1>
    <div class="pay-form">

        <p class="tcolor">输入提现金额</p>
        <p><input min="1" oninput="value=moneyLimit(value)" class="custom-sum" type="text" name="custom" placeholder="钱包余额{{ $TS['wallet']['balance'] / 100 }}"></p>
        <p><input class="custom-sum" type="text" name="account" placeholder="输入支付宝账号"></p>
        <p class="tcolor">选择提现方式</p>
        <div class="pay-way">
            <img src="{{ asset('zhiyicx/plus-component-pc/images/pay_pic_zfb_on.png') }}"/>
            <input class="hide" id="alipay" type="radio" name="payway" value="alipay" checked>
            {{-- <label class="opt" for="wxpay">微信<input class="hide" id="wxpay" type="radio" name="payway" value="wx"></label> --}}
        </div>

        <button class="pay-btn" id="J-pay-btn">提现</button>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
$('.pay-sum label').on('click', function(){
    $('.pay-sum label').removeClass('active');
    $(this).addClass('active');
})
$('.pay-way label').on('click', function(){
    $('.pay-way label').removeClass('active');
    $(this).addClass('active');
})
$('#J-pay-btn').on('click', function(){
    var payway = $('[name="payway"]:checked').val();
    var custom = $('[name="custom"]').val();
    if (custom == '') {
        $('[name="custom"]').focus();
        return false;
    }
    var account = $('[name="account"]').val();
    if (account == '') {
        $('[name="account"]').focus();
        return false;
    }

    var params = {
        type: payway,
        value: custom * 100,
        account: account
    };

    $.ajax({
        url: '/api/v2/wallet/cashes',
        type: 'POST',
        data: params,
        dataType: 'json',
        error: function(xml) {
            showError(xml.responseJSON);
        },
        success: function(res) {
            noticebox('提现成功，请等待管理员审核', 1, "{{ route('pc:wallet', ['type'=>3]) }}");
        }
    });
});


</script>
@endsection