@section('title') 充值 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="pay-box">
{{-- <form action="/account/payway" method="get" target="_blank" accept-charset="utf-8"> --}}
    <h1 class="title"> 充值 </h1>
    <div class="pay-form">

        <p class="tcolor">设置充值金额</p>
        <div class="pay-sum">
            <label class="opt" for="sum10">¥10.00<input class="hide" id="sum10" type="radio" name="sum" value="10"></label>
            <label class="opt active" for="sum50">¥50.00<input class="hide" id="sum50" type="radio" name="sum" value="5000" checked></label>
            <label class="opt" for="sum100">¥100.00<input class="hide" id="sum100" type="radio" name="sum" value="10000"></label>
        </div>

        <p><input min="1" oninput="value=moneyLimit(value)" class="custom-sum" type="text" name="custom" placeholder="自定义充值金额"></p>

        <p class="tcolor">选择充值方式</p>
        <div class="pay-way">
            <img src="{{ asset('zhiyicx/plus-component-pc/images/pay_pic_zfb_on.png') }}"/>
            <input class="hide" id="alipay" type="radio" name="payway" value="alipay_pc_direct" checked>
            {{-- <label class="opt" for="wxpay">微信<input class="hide" id="wxpay" type="radio" name="payway" value="wx"></label> --}}
        </div>

        <button type="submit" class="pay-btn" id="J-pay-btn">充值</button>
    </div>
{{-- </form> --}}
</div>
<a id="payurla" href="" target="_blank"><b id="payurlc"></b></a>
@endsection

@section('scripts')
<script src="{{ asset('zhiyicx/plus-component-pc/js/pingpp.js')}}"></script>
<script type="text/javascript">
$('.pay-sum label').on('click', function(){
    $('.pay-sum label').removeClass('active');
    $(this).addClass('active');

    $('input[name="custom"]').val('');
})

$('input[name="custom"]').on('focus, change, keyup', function(){
    $('.pay-sum label').removeClass('active');
    $('[name="sum"]').removeAttr('checked');
})

$('.pay-way label').on('click', function(){
    $('.pay-way label').removeClass('active');
    $(this).addClass('active');
})

$('#J-pay-btn').on('click', function(){
    var sum = $('[name="sum"]:checked').val();
    var payway = $('[name="payway"]:checked').val();
    var custom = $('[name="custom"]').val();
    var params = {
        type: payway,
        amount: sum ? sum : custom * 100,
        extra: {
            success_url: "{{ route('pc:wallet') }}"
        }
    }

    $.ajax({
        url: '/api/v2/wallet/recharge',
        type: 'POST',
        data: params,
        dataType: 'json',
        error: function(xml) {
            showError(xml.responseJSON);
        },
        success: function(res) {
            var res = JSON.stringify(res.charge);
            var surl = 'http://'+window.location.host+'/account/gateway?res='+res;
            $("#payurla").attr("href", surl);
            $("#payurlc").trigger("click");
            checkStatus(res.id);
        }
    });
});

function checkStatus(id){
    var html = '<div class="tip">'+
                    '<p>请您在新打开的支付页面完成付款</p>'+
                    '<p>付款前请不要关闭此窗口</p>'+
                '</div><div class="msg">完成付款后请根据您的情况点击下面的按钮。</div>';
    layer.confirm(html, {
      move: false,
      id: 'pay_tip_pup',
      title: '充值提示',
      btn: ['支付成功','遇到问题'],
    }, function(){
        // 查询订单状态
        $.ajax({
            url: '/api/v2/wallet/charges/'+id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                window.location.href = '{{route('pc:success',['url'=>route('pc:wallet'),'time'=>'3'])}}';
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    }, function(){
        // 跳转充值失败说明页面
        window.location.href = '{{route('pc:success',['url'=>route('pc:wallet'),'time'=>'3'])}}';
    });
}
</script>
@endsection