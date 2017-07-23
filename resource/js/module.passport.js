$(function() {
    // 获取焦点
    $('input[name="login"]').focus();

    var passlod = false;
    $('#login_btn').click(function() {
        var _this = $(this);

        var login = $('input[name="login"]').val();
        if (!passlod) {
            var url = '/api/v2/tokens';
            passlod = true;
            $('#login_form').ajaxSubmit({
                type: 'post',
                url: url,
                beforeSend: function() {
                    _this.text('登录中...');
                    _this.css('cursor', 'no-drop');
                },
                success: function(res) {
                    noticebox('登录成功，跳转中...', 1);
                    window.location.href = '/passport/mid/' + res.user.id + '/token/' + res.token;
                },
                error: function(xhr, textStatus, errorThrown) {
                    for (var key in xhr.responseJSON) 
                    {
                       noticebox(xhr.responseJSON[key], 0);
                       break;
                    }
                },
                complete: function() {
                    _this.text('登录');
                    _this.css('cursor', 'pointer');
                    passlod = false;
                }
            });
        }
        return false;

    });

    // 注册提交
    $('#reg_btn').click(function() {
        var _this = $(this);
        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captchacode"]').val();
        var smscode = $('input[name="verifiable_code"]').val();
        var name = $('input[name="name"]').val();
        var password = $('input[name="password"]').val();
        var repassword = $('input[name="repassword"]').val();

        if (phone == '') {
            $('input[name="phone"]').focus();
            return false;
        }

        if (captcha == '') {
            $('input[name="captchacode"]').focus();
            return false;
        }

        if (smscode == '') {
            $('input[name="verifiable_code"]').focus();
            return false;
        }

        if (name == '') {
            $('input[name="name"]').focus();
            return false;
        }

        if (password == '') {
            $('input[name="password"]').focus();
            return false;
        }


        if (!checkPhone(phone)) {
            noticebox('请输入正确的手机号', 0);
            $('input[name="phone"]').focus();
            return false;
        }

        if (password != repassword) {
            noticebox('两次密码输入不一致', 0);
            $('input[name="repassword"]').focus();
            return false;
        }

        if (!passlod) {
            var url = '/api/v2/users';
            passlod = true;
            $('#reg_form').ajaxSubmit({
                type: 'post',
                async: false,
                url: url,
                beforeSend: function() {
                    _this.text('注册中...');
                    _this.css('cursor', 'no-drop');
                },
                success: function(res) {
                    noticebox('注册成功，跳转中...', 1, '/passport/index');
                    window.location.href = '/passport/token/' + res.token;
                },
                error: function(xhr, textStatus, errorThrown) {
                    for (var key in xhr.responseJSON) 
                    {
                       noticebox(xhr.responseJSON[key], 0);
                       break;
                    }
                },
                complete: function() {
                    _this.text('注册');
                    _this.css('cursor', 'pointer');
                    passlod = false;
                }
            });
        }
        return false;
    });

    // 找回密码提交
    $('#findpwd_btn').click(function() {
        var _this = $(this);
        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captchacode"]').val();
        var smscode = $('input[name="code"]').val();
        var password = $('input[name="password"]').val();
        var repassword = $('input[name="repassword"]').val();
        $('label.error').hide();
        if (phone == '') {
            $('input[name="phone"]').focus();
            return false;
        }

        if (captcha == '') {
            $('input[name="captchacode"]').focus();
            return false;
        }

        if (smscode == '') {
            $('input[name="code"]').focus();
            return false;
        }

        if (password == '') {
            $('input[name="password"]').focus();
            return false;
        }

        if (!checkPhone(phone)) {
            noticebox('请输入正确的手机号', 0);
            $('input[name="phone"]').focus();
            return false;
        }

        if (password != repassword) {
            noticebox('两次密码输入不一致', 0);
            $('input[name="repassword"]').focus();
            return false;
        }


        if (!passlod) {
            var url = '/passport/dofindpwd';
            passlod = true;
            $('#findpwd_form').ajaxSubmit({
                type: 'patch',
                url: url,
                beforeSend: function() {
                    _this.text('找回中...');
                    _this.css('cursor', 'no-drop');
                },
                success: function(res) {
                    if (res.status) {
                        noticebox('修改成功，跳转中...', 1, '/passport/index');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON.code == 1000) {
                        noticebox('请输入正确的手机号', 0);
                    } else if (xhr.responseJSON.code == 1005) {
                        noticebox('该用户不存在', 0);
                    } else if (xhr.responseJSON.code == 1001) {
                        noticebox('手机验证码错误或失效', 0);
                        re_captcha();
                    }
                },
                complete: function() {
                    _this.text('找回');
                    _this.css('cursor', 'pointer');
                }
            });
        }
        return false;
    });

    // 发送短信验证码
    $('#smscode').click(function() {
        var _this = $(this);
        var type = _this.attr('type');
        if ($(this).hasClass('get_code_disable')) return false;

        var phone = $('input[name="phone"]').val();
        var captcha = $('input[name="captchacode"]').val();
        if (phone == '') {
            $('input[name="phone"]').focus();
            return false;
        }

        if (!captcha) {
            $('input[name="captchacode"]').focus();
            return false;
        }

        if (!checkPhone(phone)) {
            noticebox('请输入正确的手机号', 0);
            $('input[name="phone"]').focus();
            return false;
        }

        // 验证图形验证码
        var url = '/passport/checkcaptcha';
        $.ajax({
            type: 'post',
            url: url,
            data: { captcha: captcha },
            async: false,
            success: function(res) {
                // 发送验证码
                var url = '/api/v2/verifycodes/register'
                $.ajax({
                    type: 'post',
                    url: url,
                    data: { phone: phone },
                    success: function() {
                        var str = '等待<span id="passsec">60</span>秒';
                        _this.html(str);
                        timeDown(60);
                        $('input[name="code"]').val('');
                        noticebox('验证码发送成功', 1);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        for (var key in xhr.responseJSON) 
                        {
                           noticebox(xhr.responseJSON[key], 0);
                           break;
                        }
                    }
                }, 'json');
            },
            error: function(xhr) {
                noticebox('图形验证码错误', 0);
            }
        }, 'json');
        return false;
    })

})

// 验证手机号
var checkPhone = function(string) {
    var pattern = /^1[34578]\d{9}$/;
    if (pattern.test(string)) {
        return true;
    }
    return false;
};

// 验证码倒计时
var downTimeHandler = null;
var timeDown = function(timeLeft) {
    clearInterval(downTimeHandler);
    if (timeLeft <= 0) return;
    $('#smscode').addClass('get_code_disable');
    $('#passsec').html(timeLeft);
    downTimeHandler = setInterval(function() {
        timeLeft--;
        $('#passsec').html(timeLeft);
        if (timeLeft <= -1) {
            clearInterval(downTimeHandler);
            $('#smscode').html('获取短信验证码').removeClass('get_code_disable');
        }
    }, 1000);
};


// 刷新验证码
var re_captcha = function() {
    var url = '/passport/captcha';
    url = url + "/" + Math.random();
    $('#captchacode').attr('src', url);
    $('input[name="captchacode"]').val('');
}
