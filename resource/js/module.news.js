/**
 * 文章投稿。
 */
$('.subject-submit').on('click', function() {
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

    if (!args.title || getLength(args.title) > 20) {
        noticebox('文章标题不合法', 0);
        return false;
    }
    if (!args.subject) {
        noticebox('文章摘要不合法', 0);
        return false;
    }
    if (args.cate_id == '') {
        noticebox('请选择分类', 0);
        return false;
    }
    if (!args.content || getLength(args.content) > 5000) {
        noticebox('文章内容不合法', 0);
        return false;
    }
    if (args.tags.length < 1) {
        noticebox('请选择标签', 0);
        return false;
    }
    if (!args.image || args.image == 0) {
        noticebox('请上传封面图片', 0);
        return false;
    }

    if (notice.contribute.length > 0) {
        var isVerified = $.inArray("verified", notice.contribute);
        var isPay = $.inArray("pay", notice.contribute);
        var pay_conyribute = (parseInt(notice.pay_conyribute)/10).toFixed(1);

        if (isVerified > -1 && notice.verified == null) {

            ly.confirm('投稿提示', '成功通过平台认证的用户才能投稿，是否去认证？', '' , '去认证', function(){
                window.location.href = '/account/authenticate';
            });

            return false;
        } else if (isPay > -1) {

            var html = '<div class="exit_money">￥'+pay_conyribute+'</div>本次投稿您需要支付￥'+pay_conyribute+'元，是否继续投稿？';
            ly.confirm('投稿提示', html, '' , '投稿', function(){
                var url = '/api/v2/news/categories/'+args.cate_id+'/news';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: args,
                    dataType: 'json',
                    error: function(xml) {},
                    success: function(res, data, xml) {
                        if (xml.status == 201) {
                            noticebox(res.message, 1, '/news');
                        } else {
                            noticebox(res.message, 0);
                        }
                    }
                });
            });

            return false;
        }
    }

    var url = '/api/v2/news/categories/'+args.cate_id+'/news';
    $.ajax({
        url: url,
        type: 'POST',
        data: args,
        dataType: 'json',
        error: function(xml) {},
        success: function(res, data, xml) {
            if (xml.status == 201) {
                noticebox(res.message, 1, '/news');
            } else {
                noticebox(res.message, 0);
            }
        }
    });
});

/**
 * 赞核心Js
 * @type {Object}
 */
var digg = {
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(news_id) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/news/'+news_id+'/likes';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + news_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    $('#digg' + news_id).html('<a href="javascript:;" onclick="digg.delDigg(' + news_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });

    },
    delDigg: function(news_id) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/news/'+news_id+'/likes';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + news_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    $('#digg' + news_id).html('<a href="javascript:;" onclick="digg.addDigg(' + news_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds">' + num + '</font>人喜欢</a>');
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });
    }
};

/**
 * 文章收藏
 * @type {Object}
 */
var collect = {
    init: function() {
        collect.collectlock = 0;
    },
    addCollect: function(news_id) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/news/'+news_id+'/collections';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $collect = $('#collect' + news_id);
                    var num = $collect.attr('rel');
                    num++;
                    $collect.attr('rel', num);
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.delCollect(' + news_id + ');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs">' + num + '</font>收藏</a>');
                } else {
                    alert(res.message);
                }

                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(news_id) {
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = '/api/v2/news/'+news_id+'/collections';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $collect = $('#collect' + news_id);
                    var num = $collect.attr('rel');
                    num--;
                    $collect.attr('rel', num);
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.addCollect(' + news_id + ');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs">' + num + '</font>收藏</a>');
                } else {
                    alert(res.message);
                }

                collect.collectlock = 0;
            }
        });
    }
};

/**
 * 核心评论对象
 */
var comment = {
    // 初始化回复操作
    initReply: function(obj) {
        $('#J-comment-news').attr('to_uid', obj.to_uid);
        var _textarea = $(obj.editor);
        if (_textarea.size() == 0) _textarea = _textarea.find('input:eq(0)');
        var html = '回复@' + obj.to_comment_uname + ' ：';
        //清空输入框
        _textarea.val('');
        _textarea.val(html);
        _textarea.focus();
    },
    // 发表评论
    addComment: function(attrs, obj) {
        var _textarea = $('#mini_editor');
        if (_textarea.size() == 0) {
            return;
        }
        _textarea = _textarea.get(0);
        var strlen = getLength(_textarea.value);

        var leftnums = initNums - strlen;
        if (leftnums < 0 || leftnums == initNums) {
            noticebox('评论内容长度为1-' + initNums + '字', 0);
            return false;
        }
        if ("undefined" != typeof(this.addComment) && (this.addComment == true)) {
            return false; //不要重复评论
        }
        var formData = {
            body: _textarea.value,
        };
        if (attrs.to_uid > 0) {
            formData.reply_user = attrs.to_uid;
        }
        var url = '/api/v2/news/'+attrs.row_id+'/comments';
        var _this = this;
        obj.innerHTML = '评论中..';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    if (obj != undefined) {
                        obj.innerHTML = '评论';
                    }
                    var html  = '<div class="comment_item" id="comment_item_'+res.comment.id+'">';
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
                    var commentBox = $('#comment_box');
                    if ("undefined" != typeof(commentBox)) {
                        commentBox.prepend(html);
                        _textarea.value = '';
                        $('.nums').text(initNums);
                        /*绑定回复操作*/
                        $('.J-reply-comment').on('click', function() {
                            comment.initReply();
                        });
                    }
                    var commentNum = $('.comment_count').text();
                    $('.comment_count').text(parseInt(commentNum) + 1);
                } else {
                    noticebox(res.message, 0);
                }
            }
        });
    },
    delComment: function(comment_id, news_id) {
        var url = '/api/v2/news/' + news_id + '/comments/' + comment_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) { noticebox('删除失败请重试', 0); },
            success: function(res) {
                $('#comment_item_' + comment_id).fadeOut();
                var commentNum = $('.comment_count').text();
                $('.comment_count').text(parseInt(commentNum) - 1);
            }
        });
    }
};
