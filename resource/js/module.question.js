
/**
 * 回答收藏
 * @type {Object}
 */
var collect = {
    init: function() {
        collect.collectlock = 0;
    },
    addCollect: function(answer_id) {
        checkLogin()

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
        checkLogin()

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

var QA = {
    addComment: function (row_id, type) {
        var url = '/api/v2/question-answers/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
}