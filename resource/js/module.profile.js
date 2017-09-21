
/**
 * 赞核心Js
 * @type {Object}
 */
var digg = {
    // 给工厂调用的接口
    _init: function(attrs) {
        digg.init();
    },
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(feed_id) {
        checkLogin()

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/like';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + feed_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.delDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg><font> '+num+'</font></a>');
                } else {
                    alert(res.message);
                }
                digg.digglock = 0;
            }
        });

    },
    delDigg: function(feed_id) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/unlike';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + feed_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    $('#digg' + feed_id).html('<a href="javascript:;" onclick="digg.addDigg('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg><font> '+num+'</font></a>');
                } else {
                    alert(res.message);
                }
                digg.digglock = 0;
            }
        });
    }
};
/**
 * 收藏核心Js
 * @type {Object}
 */
var collect = {
    // 给工厂调用的接口
    _init: function(attrs) {
        collect.init();
    },
    init: function() {
        collect.collectlock = 0;
    },
    weibo: function(feed_id, page) {
        checkLogin()

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/feeds/' + feed_id + '/collections';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + feed_id);
                var num = $collect.attr('rel');
                num++;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect('+feed_id+ ', \'read\');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs"> '+num+'</font>人收藏</a>');
                } else {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.delCollect('+feed_id+');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>已收藏</a>');
                }

                collect.collectlock = 0;
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });

    },
    delWeibo: function(feed_id, page) {
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = '/api/v2/feeds/' + feed_id + '/uncollect'
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + feed_id);
                var num = $collect.attr('rel');
                num--;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect('+feed_id+', \'read\');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs"> '+num+'</font>人收藏</a>');
                } else {
                    $('#collect' + feed_id).html('<a href="javascript:;" onclick="collect.addCollect('+feed_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>收藏</a>');
                }
                collect.collectlock = 0;
            },
            error: function(xhr) {
                showError(xhr.responseJSON);
            }
        });
    },
    news: function(news_id) {
        checkLogin()

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
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.delNews('+news_id+');" class="act"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cos"> '+num+'</font></a>');
                } else {
                    noticebox(res.message, 0);
                }
                collect.collectlock = 0;
            }
        });

    },
    delNews: function(news_id) {
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
                    $('#collect' + news_id).html('<a href="javascript:;" onclick="collect.news('+news_id+');"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cos"> '+num+'</font></a>');
                } else {
                    noticebox(res.message, 0);
                }
                collect.collectlock = 0;
            }
        });
    }
};

/**
 * 微博操作
 * @type {Object}
 */
var profile = {
    delete : function(id, type) {
        var url = '';
        if (type == 'feeds') {
            url = '/api/v2/feeds/' + id;
        }
        layer.confirm('确定删除这条信息？', {icon: 3}, function(index) {
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                success: function(res) {
                    $('#feed' + id).fadeOut();
                    layer.close(index);
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            });
        });
    },
    pinneds: function (id, type) {
        if (type == 'feeds') {
            var url = '/api/v2/feeds/'+id+'/pinneds';
            pinneds(url);
        }
        if (type == 'news') {
            var url = '/api/v2/news/'+id+'/pinneds';
            pinneds(url);
        }

    },
    addComment: function (obj, type, cate) {
        var row_id = type ? obj.id : obj;
        var url = '/api/v2/'+cate+'/' + row_id + '/comments';
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

$(function() {
    $('#cover').on('change', function(e) {
        var file = e.target.files[0];
        var formDatas = new FormData();
            formDatas.append("image", file);
            $.ajax({
                url: '/api/v2/user/bg',
                type: 'POST',
                data: formDatas,
                contentType: false,
                processData: false,
                success: function(res) {
                    noticebox('更换背景图成功', 1);
                    $('.user_bg').attr('src', window.URL.createObjectURL(file));
                }
            });
    });
    // 显示回复框
    $('#feeds_list').on('click', '.J-comment-show', function() {
        checkLogin();
        var comment_box = $(this).parent().siblings('.comment_box');
        if (comment_box.css('display') == 'none') {
            comment_box.show();
        } else {
            comment_box.hide();
        }
    });
})