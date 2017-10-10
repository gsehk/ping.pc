
<script src="{{ asset('zhiyicx/plus-component-pc/js/pingpp.js')}}"></script>
<script>
//HTML DOM fully loaded, and fired window.onload later.
document.onreadystatechange = function(e)
{
    if (document.readyState === 'complete')
    {
        window.addEventListener('message', function(e){
            var params = JSON.parse(e.data);

            //ping++ 创建支付宝支付
            pingpp.createPayment(params);
        });
    }
};
</script>
