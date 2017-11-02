/**
 * 文章投稿。
 */
$('.subject-submit').on('click', function() {

    var _this = this;
    if (_this.lockStatus == 1) {
        noticebox('请勿重复提交', 0);
        return;
    }
    var args = {
        'author': $('#subject-author').val(),
        'title': $('#subject-title').val(),
        'subject': $('#subject-abstract').val(),
        'content': editor.getMarkdown(),
        'image': $('#subject-image').val(),
        'from': $('#subject-from').val(),
        'cate_id': $('#cate_id').val(),
        'news_id': $('#news_id').val() || 0
    };
    var tags = [];
    $('#J-select-tags li').each(function(index){
        tags.push($(this).data('id'));
    });
    args.tags = tags;

    if (!args.title) {
        noticebox('文章标题不能为空', 0);
        return false;
    }
    if (getLength(args.title) > 20) {
        noticebox('文章标题字数不能超过20字', 0);
        return false;
    }
    if (args.cate_id == '0') {
        noticebox('请选择栏目', 0);
        return false;
    }
    if (getLength(args.subject) > 200) {
        noticebox('摘要内容不能超过200字', 0);
        return false;
    }
    if (!args.content) {
        noticebox('文章内容不能为空', 0);
        return false;
    }
    if (getLength(args.content) > 5000) {
        noticebox('文章内容不能超过5000字', 0);
        return false;
    }
    /*if (!args.subject) { // 如果没有摘要，则截取内容前200字作为摘要
        args.subject = subString(editor.getHTML().replace(/<.*?>/ig,""), 200)
    }*/
    if (args.tags.length < 1) {
        noticebox('请选择标签', 0);
        return false;
    }
    if (!args.image || args.image == 0) {
        /*var reg = /\@\!\[\]\((\w+)\)/;
        var imgs = reg.exec(args.content);
        if (imgs != null) {
            args.image = imgs[1];
        }*/
        noticebox('请上传封面图片', 0);
        return false;
    }

    if (args.news_id > 0) {
        return news.update(args);
    }

    var isVerified = notice.contribute.verified;
    var isPay = notice.contribute.pay;
    var pay_contribute = (notice.pay_contribute * TS.BOOT['wallet:ratio']).toFixed(2);

    if (isVerified && notice.verified == null) {
        ly.confirm(formatConfirm('投稿提示', '成功通过平台认证的用户才能投稿，是否去认证？'), '去认证' , '', function(){
            window.location.href = '/account/authenticate';
        });
        return false;
    } else if (isPay && pay_contribute > 0) {
        var html = formatConfirm('投稿提示', '<div class="confirm_money">' + pay_contribute + '</div>本次投稿您需要支付' + pay_contribute + TS.BOOT.site.gold_name.name +'，是否继续投稿？');
        ly.confirm(html, '投稿' , '', function(){
           return news.create(args);
        });

        return false;
    }

    return news.create(args);
});

var news = {
    create: function (args) {
        var _this = this;
        if ( _this.lockStatus == 1) {
            return;
        }
         _this.lockStatus = 1;
        var url = '/api/v2/news/categories/'+args.cate_id+'/news';
        $.ajax({
                url: url,
                type: 'POST',
                data: args,
                dataType: 'json',
                error: function(xml) {
                    _this.lockStatus = 0;
                    layer.closeAll();
                    noticebox(xml.responseJSON.message, 0);
                },
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        layer.closeAll();
                        noticebox('投稿成功，请等待审核', 1, '/news');
                    } else {
                        noticebox(res.message, 0);
                        _this.lockStatus = 0;
                    }
                }
            });
    },
    update: function (args) {
        var _this = this;
        if ( _this.lockStatus == 1) {
            return;
        }
         _this.lockStatus = 1;
        var url = '/api/v2/news/categories/'+args.cate_id+'/news/'+args.news_id;
        $.ajax({
                url: url,
                type: 'PATCH',
                data: args,
                dataType: 'json',
                error: function(xml) {
                    noticebox(xml.responseJSON.message, 0);
                    _this.lockStatus = 0;
                },
                success: function(res, data, xml) {
                    if (xml.status == 204) {
                        noticebox('修改成功，请等待审核', 1, '/news');
                    } else {
                        noticebox(res.message, 0);
                        _this.lockStatus = 0;
                    }
                }
            });
    },
    delete: function (news_id, cate_id) {
        var url = '/api/v2/news/categories/'+cate_id+'/news/'+news_id;
        layer.confirm(confirmTxt + '确定删除这篇文章？', function() {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                error: function(error) {showError(error.responseJSON);},
                success: function(res) {
                    noticebox('删除成功', 1, '/news');
                }
            });
        });
    },
    pinneds: function (news_id) {
        var url = '/api/v2/news/'+news_id+'/pinneds';
        pinneds(url);
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/news/' + row_id + '/comments';
        comment.support.row_id = row_id;
        comment.support.position = type;
        comment.support.editor = $('#J-editor'+row_id);
        comment.support.button = $('#J-button'+row_id);
        comment.publish(url, function(res){
            $('.nums').text(comment.support.wordcount);
            $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
        });
    }
};

function subString(str, len, hasDot) {
    hasDot = hasDot ? hasDot : false;
    var newLength = 0;
    var newStr = "";
    var chineseRegex = /[^\x00-\xff]/g;
    var singleChar = "";
    var strLength = str.replace(chineseRegex, "**").length;
    for (var i = 0; i < strLength; i++) {
        singleChar = str.charAt(i).toString();
        newLength++;
        if (newLength > len) {
            break;
        }
        newStr += singleChar;
    }

    if (hasDot && strLength > len) {
        newStr += "...";
    }
    return newStr;
}