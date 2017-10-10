
<script src="{{ asset('zhiyicx/plus-component-pc/js/pingpp.js')}}"></script>
<script>

var charge = {!!$charge!!};
// console.log(charge);
// ping++ 创建支付宝支付
pingpp.createPayment(charge);
</script>
