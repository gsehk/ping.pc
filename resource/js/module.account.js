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
            
            args.set(sel.attr('location'), sel.val());
            if (sel.attr('name') == 'location') {
                if (sel.val() == locate) {
                    delete args.data.name;
                }
            }
        }
    };
        return args.get();
    };
    var arg = getArgs();
    if (arg.intro && getLength(args.intro) > 50) {
        noticebox('个人简介不能超过50个字符', 0);
        return false;
    }
    $.ajax({
        url: '/api/v2/user',
        type: 'PATCH',
        data: arg,
        dataType: 'json',
        error: function(xml) {
            noticebox('资料修改失败', 0, 'refresh');
        },
        success: function(res) {
            noticebox('资料修改成功', 1, 'refresh');
        }
    });
});