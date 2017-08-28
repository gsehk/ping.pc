// 地区初始化
var init = function(pid) {
    // 获取树形结构的子树
    var option1 = '<option value="0">请选择</option>';
    var option2 = '<option value="0">请选择</option>';
    var option3 = '<option value="0">请选择</option>';
    var sel_city, sel_area;
    $.getJSON('/api/v1/areas', { pid: pid }, function(pro) {
        if (pro.status == true) {
            $.each(pro.data, function(i, n) {
                if (n.name == arrSelect[0]) { sel_city = n.id;}
                var selected1 = (n.name == arrSelect[0]) ? 'selected="selected"' : '';
                option1 += '<option value="' + n.id + '" ' + selected1 + '>' + n.name + '</option>'
            });
            $('#province').html(option1);
            if (sel_city) {
                $.getJSON('/api/v1/areas', { pid: sel_city }, function(city) {
                    if (city.status == true) {
                        $.each(city.data, function(i, n) {
                            if (n.name == arrSelect[1]) { sel_area = n.id;}
                            var selected2 = (n.name == arrSelect[1]) ? 'selected="selected"' : '';
                            option2 += '<option value="' + n.id + '" ' + selected2 + '>' + n.name + '</option>'
                        });
                        $('#city').html(option2);
                    }
                    if (sel_area) {
                        $.getJSON('/api/v1/areas', { pid: sel_area }, function(area) {

                            if (area.status == true) {
                                $.each(area.data, function(i, n) {
                                    var selected3 = (n.name == arrSelect[2]) ? 'selected="selected"' : '';
                                    option3 += '<option value="' + n.id + '" ' + selected3 + '>' + n.name + '</option>'
                                });
                                $('#area').html(option3);
                            }
                        });
                    } else {
                        $('#area').html(option3);
                    }
                });
            } else {
                $('#city').html(option2);
            }
        }
    });
};
var getArea = function(obj) {
    var id = $(obj).attr('id');
    var pid = $(obj).val();
    var option2 = '<option value="0">请选择</option>';
    var option3 = '<option value="0">请选择</option>';
    $.getJSON('/api/v1/areas', { pid: pid }, function(area) {
        if (area.status == true) {
            switch (id) {
                case 'province':
                    $.each(area.data, function(i, n) {
                        option2 += '<option value="' + n.id + '">' + n.name + '</option>'
                    });
                    $('#city').html(option2);
                    $('#area').html(option3);
                    break;
                case 'city':
                    $.each(area.data, function(i, n) {
                        option3 += '<option value="' + n.id + '">' + n.name + '</option>'
                    });
                    $('#area').html(option3);
                    break;
            }
        }
    });
};

/* 基本资料更新 */
$('#J-user-info').on('click', function(e) {
    var getArgs = function() {
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
                if ($(inp[i]).attr('name') == 'sex') {
                    args.set('sex', $('[name="sex"]:checked').val());
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
    var args = getArgs();
    if (args.intro && getLength(args.intro) > 50) {
        noticebox('个人简介不能超过50个字符', 0);
        return false;
    }
    args['location'] = args['p'] + ' ' + args['c'] + ' ' + args['a'];
    $.ajax({
        url: '/api/v2/user',
        type: 'PATCH',
        data: args,
        dataType: 'json',
        error: function(xml) {
            noticebox('资料修改失败', 0, 'refresh');
        },
        success: function(res) {
            noticebox('资料修改成功', 1, 'refresh');
        }
    });
});

/*  提交用户认证信息*/
$('#J-user-authenticate').on('click', function(e) {
    var getArgs = function() {
        var inp = $('#J-input input').toArray();
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
        error: function(xml) {
            noticebox('操作失败', 0);
        },
        success: function(res) {
            noticebox('操作成功', 1, 'refresh');
        }
    });
};