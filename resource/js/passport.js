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
        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captchacode"]').val();
        var smscode = $('input[name="code"]').val();
        var name = $('input[name="name"]').val();
        var password = $('input[name="password"]').val();
        var repassword = $('input[name="repassword"]').val();
        $('label.error').hide();
        if (phone == '') {
            $('#phone_tip').html('请输入手机号').show();
            return false;
        }

        if (!checkPhone(phone)) {
            $('#phone_tip').html('请输入正确的手机号').show();
            return false;
        }

        if (captcha == '') {
            $('#captcha_tip').html('请输入图形验证码').show();
            return false;
        }

        if (name == '') {
            $('#name_tip').html('请输入昵称').show();
            return false;
        }

        if (repassword == '') {
            $('#repassword_tip').html('两次密码输入不一致').show();
            return false;
        }


        var url = '/passport/doregister';
        $('#reg_form').ajaxSubmit({
            type: 'post',
            url: url,
            success : function(res) {
                window.location.href = '/home/index';
            },
            error : function(xhr, textStatus, errorThrown){
               if (xhr.responseJSON.code == 1000) {
                    $('#phone_tip').html('请输入正确的手机号');
               } else if (xhr.responseJSON.code == 1003) {
                    $('#user_tip').html('用户名格式错误');
               } else if (xhr.responseJSON.code == 1004) {
                    $('#user_tip').html('用户名已存在');
               } else if (xhr.responseJSON.code == 1010) {
                    $('#phone_tip').html('手机号已存在');
               } else if (xhr.responseJSON.code == 1000) {
                    $('#smscode_tip').html('手机验证码错误或失效');
               }
            }
        });
        return false;
    });

    // 发送短信验证码
    $('#smscode').click(function(){
        var _this = $(this);
        if ($(this).hasClass('get_code_disable')) return false;

        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captchacode"]').val();
        if (phone == '') {
            $('#phone_tip').html('请输入手机号').show();
            return false;
        }

        if (!checkPhone(phone)) {
            $('#phone_tip').html('请输入正确的手机号').show();
            return false;
        }

        if (!captcha) {
            $('#captcha_tip').html('请输入图形验证码').show();
            return false;
        }

        $('#phone_tip').html('').hide();
        $('#captcha_tip').html('').hide();

        // 验证图形验证码
        var url = '/passport/checkcaptcha';
        $.ajax({
            type: 'post',
            url: url,
            data: {captcha: captcha},
            async: false,
            success: function (res) {
                if (!res.status) {
                    $('#captcha_tip').html('图形验证码错误').show();
                    re_captcha();
                } else {
                    // 发送验证码
                    var url = API + '/auth/phone/send-code';
                    var type = 'register';
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: {phone: phone, type: type},
                        success: function(){
                            var str = '等待<span id="passsec">60</span>秒';
                            _this.html(str);
                            timeDown(60);
                            $('#smscode_tip').html('').hide();
                            $('input[name="code"]').val('');
                        },
                        error : function(xhr, textStatus, errorThrown){
                            if (xhr.responseJSON.code == 1010) {
                                $('#phone_tip').html('手机号已存在').show();
                            } else {
                                $('#smscode_tip').html('发送失败，请稍后再试').show();
                            }
                        }
                    }, 'json');
                }
            }
        }, 'json');
        return false;
    })

})

// 验证手机号
var checkPhone = function (string) {    
    var pattern = /^1[34578]\d{9}$/;    
    if (pattern.test(string)) {        
        return true;    
    }
    return false;
};

// 验证码倒计时
var downTimeHandler = null;
var timeDown = function(timeLeft){
    clearInterval(downTimeHandler);
    if(timeLeft <= 0) return ;
    $('#smscode').addClass('get_code_disable');
    $('#passsec').html(timeLeft);
    downTimeHandler = setInterval(function(){
        timeLeft--;
        $('#passsec').html(timeLeft);
        if(timeLeft <= -1){
            clearInterval(downTimeHandler);
            $('#smscode').html('获取短信验证码').removeClass('get_code_disable');
        }
    }, 1000);
};


// 刷新验证码
var re_captcha = function(){
	var url = '/passport/captcha';
	url = url + "/" + Math.random();
	$('#captchacode').attr('src', url);
    $('input[name="captchacode"]').val('');
}