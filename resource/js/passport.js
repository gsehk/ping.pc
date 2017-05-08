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
            captchacode:{
                required:true,
                checkCaptcha:true
            },
			smscode: "required",
			name: "required",
			password: "required",
			repassword: "required",
		},
		messages: {
			phone: "手机号不能为空",
            captchacode: {
                required: "图形验证码不能为空",
                checkCaptcha: "图形验证码错误"
            },
            smscode: "短信验证码不能为空",
            name: "昵称不能为空",
            password: "密码不能为空",
            repassword: "请确认密码",
		},
        errorPlacement: function(error,element) {
            var name = element.attr('name');
            error.insertAfter('#' + name);
        },
        submitHandler:function(form){

            // 验证手机验证码
            var url = '/passport/doregister';
            $('#reg_form').ajaxSubmit({
                type: 'post',
                url: url,
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


    // 添加图形验证码验证
    $.validator.addMethod("checkCaptcha", function(value, element, params){
        var captcha = $('input[name="captchacode"]').val();

        if ((value.length > 0 && value.length < 5) || (value.length > 5)) {
            return false;
        } else {
            console.log(value.length);
            $.ajax({
                type: 'post',
                url: 'checkcaptcha',
                async: false,
                data: {captcha: captcha},
                success: function(res){
                    if (res.status) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }, 'json');
        }
    });


})


/**
 * [re_captcha 刷新验证码]
 * @Author Foreach<hhhcode@outlook.com>
 * @return {[type] [description]
 */
function re_captcha() {
	var url = '/passport/captcha';
	url = url + "/" + Math.random();
	$('#captchacode').attr('src', url);
}