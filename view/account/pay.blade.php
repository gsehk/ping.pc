@section('title') 充值 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="pay-box">
    <h1 class="title"> 充值 </h1>
    <div class="pay-form">

        <p class="tcolor">设置充值金额</p>
        <div class="pay-sum">
            <label class="opt" for="sum10">¥10<input class="hide" id="sum10" type="radio" name="sum" value="10"></label>
            <label class="opt active" for="sum50">¥50<input class="hide" id="sum50" type="radio" name="sum" value="50" checked></label>
            <label class="opt" for="sum100">¥100<input class="hide" id="sum100" type="radio" name="sum" value="100"></label>
        </div>

        <p><input class="custom-sum" type="text" name="custom" placeholder="自定义充值金额"></p>

        <p class="tcolor">选择充值方式</p>
        <div class="pay-way">
            <label class="opt active" for="alipay">支付宝<input class="hide" id="alipay" type="radio" name="payway" value="alipay_qr" checked></label>
            <label class="opt" for="wxpay">微信<input class="hide" id="wxpay" type="radio" name="payway" value="wx"></label>
        </div>

        <button class="pay-btn" id="J-pay-btn">充  值</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
$('.pay-sum label').on('click', function(){
    $('.pay-sum label').removeClass('active');
    $(this).addClass('active');
})
$('.pay-way label').on('click', function(){
    $('.pay-way label').removeClass('active');
    $(this).addClass('active');
})
$('#J-pay-btn').on('click', function(){
    var sum = $('[name="sum"]:checked').val();
    var payway = $('[name="payway"]:checked').val();
    var custom = $('[name="custom"]').val();

    $.ajax({
        url: '/api/v2/wallet/recharge',
        type: 'POST',
        data: {type: payway, amount: sum ? sum : custom},
        dataType: 'json',
        error: function(xml) {
            noticebox('充值失败', 0);
        },
        success: function(res) {
            console.log(res)
        }
    });
});


</script>
@endsection