
// 地区初始化
var init = function(pid) {
  // 获取树形结构的子树
  var option1 = '<option value="0">请选择</option>';
   var option2 = '<option value="0">请选择</option>';
    var option3 = '<option value="0">请选择</option>';
  $.getJSON('/api/v1/areas', {pid:pid}, function(pro){
    if (pro.status == true) {
      $.each(pro.data, function(i, n) {
        var selected1 = (n.id == arrSelect[0]) ? 'selected="selected"' : '';
        option1 += '<option value="' + n.id +'" ' + selected1 + '>' + n.name + '</option>'
      });
      $('#province').html(option1);

      if (arrSelect[0]) {
        $.getJSON('/api/v1/areas', {pid:arrSelect[0]}, function(city){

          if (city.status == true) {
            $.each(city.data, function(i, n) {
              var selected2 = (n.id == arrSelect[1]) ? 'selected="selected"' : '';
              option2 += '<option value="' + n.id +'" ' + selected2 + '>' + n.name + '</option>'
            });

            $('#city').html(option2);
          }
        });
      }else{
          $('#city').html(option2);
      }
      if (arrSelect[1]) {
        $.getJSON('/api/v1/areas', {pid:arrSelect[1]}, function(area){

          if (area.status == true) {
            $.each(area.data, function(i, n) {
              var selected3 = (n.id == arrSelect[2]) ? 'selected="selected"' : '';
              option3 += '<option value="' + n.id +'" ' + selected3 + '>' + n.name + '</option>'
            });
            
            $('#area').html(option3);
          }
        });
      }else{
        $('#area').html(option3);
      }
    }
  });
};
var getArea = function (obj) {
    var id = $(obj).attr('id');
    var pid = $(obj).val();
    var option2 = '<option value="0">请选择</option>';
    var option3 = '<option value="0">请选择</option>';
    $.getJSON('/api/v1/areas', {pid:pid}, function(area){
        if (area.status == true) {
          switch(id) {
              case 'province':
                  $.each(area.data, function(i, n) {
                      option2 += '<option value="' + n.id +'">' + n.name + '</option>'
                  });
                  $('#city').html(option2);
                  $('#area').html(option3);
              break;
              case 'city':
                  $.each(area.data, function(i, n) {
                      option3 += '<option value="' + n.id +'">' + n.name + '</option>'
                  });
                  $('#area').html(option3);
              break;
          }
        }
    });
};


/* 获取页面中所有文本域，表单，选择器的值 */
var getArgs = function () {
  var inp = $('#J-input input,#J-input select').toArray();
  var sel;
  for (var i in inp) {
    sel = $(inp[i]);
    if (sel.val()) {
      args.set(sel.attr('name'), sel.val());
      if (sel.attr('name') == 'name') {
        if (sel.val() == username) {
            delete args.data.name;
        }
      }
      if ($(inp[i]).attr('name') == 'province') {
          args.set('p', sel.find("option:selected").text());
      }
      if ($(inp[i]).attr('name') == 'city') {
          args.set('c', sel.find("option:selected").text());
      }
      if ($(inp[i]).attr('name') == 'area') {
          args.set('a', sel.find("option:selected").text());
      }
    }
  };

  return args.get();
};

$('#J-submit').on('click', function(e){
    var args = getArgs();
    args['location'] = args['p']+' '+args['c']+' '+args['a'];
    $.ajax({
        url: '/api/v1/users',
        type: 'PATCH',
        data: args,
        dataType: 'json',
        error:function(xml){
          $('.success_div').html('<div class="set_success s_bg"><img src="" />资料修改失败</div>').fadeIn();
        },
        success:function(res){$('.success_div').html('<div class="set_success s_bg"><img src="" />资料修改成功</div>').fadeIn();}
    });
    setTimeout("$('.success_div').fadeOut(1000)", 3000);
});

var resetPwd = function () {

    var password = $('#password').val();
    var new_pwd = $('#new_password').val();
    var token = $('#token').val();
    $.ajax({
        url: '/api/v1/users/password',
        type: 'PATCH',
        data: {password:password,new_password:new_pwd,_token:token},
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        error:function(xml){
          $('.success_div').html('<div class="set_success s_bg"><img src="" />修改失败</div>').fadeIn();
        },
        success:function(res){$('.success_div').html('<div class="set_success s_bg"><img src="" />修改成功</div>').fadeIn();}
    });
    setTimeout("$('.success_div').fadeOut(1000)", 3000);
};

/*  提交用户认证信息*/
var userVerif = function () {
    var getArgs = function () {
      var inp = $('#auth_form input').toArray();
      var sel;
      for (var i in inp) {
        sel = $(inp[i]);
        args.set(sel.attr('name'), sel.val());
      };
      return args.get();
    };
    var url = $('#J-user-verif').data('url');
    $.ajax({
        url: url,
        type: 'POST',
        data: getArgs(),
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', TOKEN);
    　　},
        error:function(xml){
          // if (xml.responseJSON.code == 1004) {}
          $('.success_div').html('<div class="set_success s_bg"><img src="" />认证失败</div>').fadeIn();
        },
        success:function(res){$('.success_div').html('<div class="set_success s_bg"><img src="" />认证成功</div>').fadeIn();}
    });
    setTimeout("$('.success_div').fadeOut(1000)", 3000);
};

/*  删除用户认证资料 重新认证*/
var delUserAuth = function(uid) {
    if (uid) {
      var url = '/profile/delUserAuth';
      $.ajax({
        url: url,
        type: 'DELETE',
        data: {user_id:uid},
        dataType: 'json',
        error:function(xml){
        },
        success:function(res){
          if (res.code == 1) {
              window.location.href = res.uri;
          } else {
              alert('操作失败请重试')
          }
        }
      });
    }
};