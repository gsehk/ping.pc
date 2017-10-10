
<script src="{{ asset('zhiyicx/plus-component-pc/js/pingpp.js')}}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/jquery.min.js')}}"></script>
<script>
var charge = <?php echo json_encode($charge) ?>;
// ping++ 创建支付宝支付
pingpp.createPayment(charge);
</script>
