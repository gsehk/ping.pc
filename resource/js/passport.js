$(function(){
    $('#login_btn').click(function(){

    	var url = '/passport/dologin';
		$('#login_form').ajaxSubmit({
		    type: 'post',
		    url: url,
		    success : function() {
		    	window.location.href = '/home/index';
		    },
			error : function(xhr, textStatus, errorThrown){
			　　if (xhr.status == 422) {
					$('#login_tip').html('用户名或密码错误');
				}
			}
		});
		return false;
    });

    $('#reg_btn').click(function(){
    	$('#reg_form').submit();	
    });

    $("#reg_form").validate({
		rules: {
			phone: "required",
			captcha: "required",
			smscode: "required",
			name: "required",
			password: "required",
			repassword: "required",
		},
		messages: {
			phone: "手机号不能为空"
		},
        submitHandler:function(form){
            alert();return false;
            var captcha = $('input[name="captcha"]').val();
            // 验证图形验证码
            $.ajax({
                type: 'post',
                url: 'checkcaptcha',
                data: {captcha: captcha},
                success: function(res){
                    if (res.status) {
                        
                    } else {
                        
                    }
                }
            });

            // 验证手机验证码


            var url = '/passport/doregister';
            $('#reg_form').ajaxSubmit({
                type: 'post',
                url: url,
                beforeSend: function (xhr) {
            　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
            　　},
                success : function(res) {
                    if (res.code == 1000) {

                    } else if (res.code == 1001) {

                    } else if (res.code == 1002) {

                    } else if (res.code == 1003) {

                    }
                }
            });
            return false;
        }    
    });


})


/**
 * [re_captcha 刷新验证码]
 * @Author Foreach<hhhcode@outlook.com>
 * @return {[type] [description]
 */
function re_captcha() {
	var url = '{{ URL("passport/captcha") }}';
	url = url + "/" + Math.random();
	$('#captcha').attr('src', url);
}