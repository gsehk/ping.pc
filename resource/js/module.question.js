
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
    },
    adoptions: function (question_id, answer_id) {
        var url = '/api/v2/questions/' + question_id + '/adoptions/' + answer_id;
        $.ajax({
            url: url,
            type: 'PUT',
            dataType: 'json',
            success: function(res) {
                noticebox(res.message, 1, '/question/' + question_id);
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    },
    delAnswer: function (question_id, answer_id) {
        url = '/api/v2/question-answers/' + answer_id;
         $.ajax({
             url: url,
             type: 'DELETE',
             dataType: 'json',
             success: function(res, data, xml) {
                 if (xml.status == 204) {
                     $('#answer' + answer_id).fadeOut();
                     $('.qs' + question_id).text(parseInt($('.qs' + question_id).text())-1);
                 }
             },
             error: function(xhr){
                 showError(xhr.responseJSON);
             }
         });
    },
    share: function (answer_id) {
        var bdDesc = $('#answer' + answer_id).find('.answer-body').text();
        var reg = /<img src="(.*?)".*?/;
        var imgs = reg.exec($('.show-answer-body').html());
        var img = imgs != null ? imgs[1] : '';
        bdshare.addConfig('share', {
            "tag" : "share_answerlist",
            'bdText' : bdDesc,
            'bdDesc' : '',
            'bdUrl' : SITE_URL + '/question/answer/' + answer_id,
            'bdPic': img
        });

        console.log(bdDesc);
    },
    look: function (answer_id, money, question_id) {
        ly.confirm(formatConfirm('围观支付', '本次围观您需要支付' + money + '元，是否继续围观？'), '' , '', function(){
            var url ='/api/v2/question-answers/' + answer_id + '/onlookers';

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    noticebox('围观成功', 1, '/question/' + question_id);
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            });
        });
    }
};

var question = {
    addComment: function (row_id, type) {
        var url = '/api/v2/questions/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.support.top = false;
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    },
    delQuestion: function(question_id) {
        ly.confirm(formatConfirm('提示', '确定删除这条信息？'), '' , '', function(){
            var url ='/api/v2/questions/' + question_id;
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                success: function(res) {
                    noticebox('删除成功', 1, '/question');
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            });
        });
    },
    share: function (question_id) {
        var bdDesc = $('.richtext').text();
        var reg = /<img src="(.*?)".*?/;
        var imgs = reg.exec($('.show-body').html());
        var img = imgs != null ? imgs[1] : '';
        bdshare.addConfig('share', {
            "tag" : "share_questionlist",
            'bdText' : bdDesc,
            'bdDesc' : "",
            'bdUrl' : SITE_URL + '/question/' + question_id,
            'bdPic': img
        });
    },
    selected: function (question_id, money) {
        var html = formatConfirm('精选问答支付', '<div class="confirm_money">￥' + money + '</div>本次申请精选您需要支付' + money + '元，是否继续申请？');

        ly.confirm(html, '' , '', function(){
            var url ='/api/v2/user/question-application/' + question_id;
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        noticebox('申请成功', 1);
                    }
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            });
        });
    }
};