
var args = {
  data: {},
  set: function(name, value) {
    this.data[name] = value;
    return this;
  },
  get: function () {
    return this.data;
  }
};

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
      }
      console.log(option2);
      $('#province').html(option1);
      $('#city').html(option2);
      $('#area').html(option3);
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
              break;
              case 'city':
                  $.each(area.data, function(i, n) {
                      option3 += '<option value="' + n.id +'">' + n.name + '</option>'
                  });
                  $('#area').html(option3);
              break;
          }
          console.log(area);
        }
    });
};
init(1);


/* 获取页面中所有文本域，表单，选择器的值 */
var getArgs = function () {
  var inp = $('#J-input input,#J-input select').toArray();
  var sel;
  for (var i in inp) {
    sel = $(inp[i]);
    if (sel.val()) {
      args.set(sel.attr('name'), sel.val());

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
    console.log(args);
    $.ajax({
        url: '/api/v1/users',
        type: 'PATCH',
        data: args,
        dataType: 'json',
        beforeSend: function (xhr) {
    　　　xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
    　　},
        error:function(){$('.success_div').html('<div class="set_success"><img src="" />资料修改失败</div>');},
        success:function(res){$('.success_div').html('<div class="set_success s_bg"><img src="" />资料修改成功</div>');}
    });
    setTimeout("$('.success_div').fadeOut(1000)", 3000);
});
