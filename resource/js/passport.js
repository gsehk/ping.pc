$(function(){
    $('#login_btn').click(function(){

    	var url = '/passport/login';
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
    })
}) 