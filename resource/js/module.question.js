
var comment = {
    publish: function(obj) {
        checkLogin();
        var to_uid = $(obj).attr('to_uid') || 0;
        var rowid = $(obj).attr('row_id') || 0;
        var editor = $('#mini_editor');

        var formData = { body: editor.val() };
        if (!editor.val()) {
            layer.msg('评论内容不能为空');return;
        }
        if (to_uid > 0) {
            formData.reply_user = to_uid;
        }
        var url = '/api/v2/question-answers/' + rowid + '/comments';
        obj.innerHTML = '评论中..';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (obj != undefined) {
                    obj.innerHTML = '评论';
                    $('.nums').text(initNums);
                    editor.val('');
                }
                var html  = '<div class="comment_item" id="comment'+res.comment.id+'">';
                    html += '    <dl class="clearfix">';
                    html += '        <dt>';
                    html += '            <img src="'+AVATAR+'" width="50">';
                    html += '        </dt>';
                    html += '        <dd>';
                    html += '            <span class="reply_name">'+NAME+'</span>';
                    html += '            <div class="reply_tool">';
                    html += '                <span class="reply_time">刚刚</span>';
                    html += '                <span class="reply_action"><i class="icon iconfont icon-gengduo-copy"></i></span>';
                    html += '            </div>';
                    html += '            <div class="replay_body">'+formData.body+'</div>';
                    html += '        </dd>';
                    html += '    </dl>';
                    html += '</div>';

                    $('#comment_box').prepend(html);
                    $('.comment_count').text(parseInt($('.comment_count').text())+1);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
                obj.innerHTML = '评论';
            }
        });
    },
    delete: function(comment_id, post_id) {
        var url = '/api/v2/question-answers/' + post_id + '/comments/' + comment_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#comment'+comment_id).fadeOut();
                $('.comment_count').text(parseInt($('.comment_count').text())-1);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    },
    pinneds: function() {
        return;
    }
}

/**
 * 回答收藏
 * @type {Object}
 */
var collect = {
    init: function() {
        collect.collectlock = 0;
    },
    addCollect: function(answer_id) {
        checkLogin();
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/user/question-answer/collections/'+answer_id;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $collect = $('#collect' + answer_id);
                    var num = $collect.attr('rel');
                    num++;
                    $collect.attr('rel', num);
                    $('#collect' + answer_id).html('<a href="javascript:;" onclick="collect.delCollect('+answer_id+');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">'+num+' </font>收藏</a>');
                } else {
                    alert(res.message);
                }
                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(answer_id) {
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/user/question-answer/collections/'+answer_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $collect = $('#collect' + answer_id);
                    var num = $collect.attr('rel');
                    num--;
                    $collect.attr('rel', num);
                    $('#collect' + answer_id).html('<a href="javascript:;" onclick="collect.addCollect('+answer_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">'+num+' </font>收藏</a>');
                } else {
                    alert(res.message);
                }
                collect.collectlock = 0;
            }
        });
    }
};

/**
 * 赞核心Js
 * @type {Object}
 */
var digg = {
    _init: function(attrs) {
        digg.init();
    },
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(answer_id) {
        checkLogin();
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/question-answers/' + answer_id + '/likes';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + answer_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    $('#digg' + answer_id).html('<a href="javascript:;" onclick="digg.delDigg('+answer_id+');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">'+num+' </font>人喜欢</a>');
                } else {
                    alert(res.message);
                }
                digg.digglock = 0;
            }
        });
    },
    delDigg: function(answer_id) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/question-answers/' + answer_id + '/likes';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + answer_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    $('#digg' + answer_id).html('<a href="javascript:;" onclick="digg.addDigg('+answer_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">'+num+' </font>人喜欢</a>');
                } else {
                    alert(res.message);
                }
                digg.digglock = 0;
            }
        });
    }
};