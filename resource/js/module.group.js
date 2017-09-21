// var group={};

var post = {};

/**
 * 上传后操作
 * @return void
 */
post.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="post.showImg();" src="' + image.src + '" tid="' + task_id + '"/>';
    var del = '<span class="imgdel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-close"></use></svg></span>'
    $('#' + 'fileupload_1_' + f.index).css('border', 'none').html(img + del);
};
post.showImg = function(){
    layer.photos({
        photos: '#file_upload_1-queue'
        ,anim: 0
        ,move: false
    });
};

post.createPost = function (group_id) {
    checkLogin()

    var images = [];
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });

    var data = {
        title: $('#post_title').val(),
        content: $('#feed_content').val(),
        images: images,
        group_post_mark: MID + new Date().getTime(),
    };

    if (!data.title || getLength(data.title) > 30) {
        noticebox('标题的字数为1 - 30个字符', 0);
        return false;
    }

    var strlen = getLength(data.content);
    var leftnums = initNums - strlen;
    if ((leftnums < 0 || leftnums == initNums) && data.images.length < 1) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }
    $.ajax({
        url: '/api/v2/groups/' + group_id + '/posts',
        type: 'post',
        data: data,
        success: function(res) {
            noticebox('发布成功', 1);
            $('.feed_picture').html('').hide();
            $('#post_title').val('');
            $('#feed_content').val('');
            post.afterCreatePost(group_id, res.id);
        },
        error: function(xhr){
            showError(xhr.responseJSON);
        }
    })
};

post.afterCreatePost = function (group_id, post_id) {
    var url = '/group/getPost';
    $.ajax({
        url: url,
        type: 'get',
        data: {
            group_id: group_id,
            post_id: post_id
        },
        dataType: 'json',
        success: function(res) {
            if ($('#feeds_list').find('.no_data_div').length > 0) {
                $('#feeds_list').find('.no_data_div').remove();
            }
            $(res.data).hide().prependTo('#feeds_list').fadeIn('slow');
            $("img.lazy").lazyload({effect: "fadeIn"});
        }
    })
};

post.delPost = function(group_id, post_id) {
    layer.confirm(confirmTxt + '确定删除这条信息？', {}, function() {
        var url ='/api/v2/groups/' + group_id + '/posts/' + post_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#feed' + post_id).fadeOut();
                layer.closeAll();
            },
            error: function(xhr){
                showError(xhr.responseJSON);
            }
        });
    });
};
post.addComment = function (row_id, group_id, type) {
    var url = '/api/v2/groups/' + group_id + '/posts/' + row_id + '/comments';
    comment.support.row_id = row_id;
    comment.support.position = type;
    comment.support.editor = $('#J-editor'+row_id);
    comment.support.button = $('#J-button'+row_id);
    comment.publish(url, function(res){
        $('.nums').text(comment.support.wordcount);
        $('.cs'+row_id).text(parseInt($('.cs'+row_id).text())+1);
    });
}
/**
 * 赞核心Js
 * @type {Object}
 */
var digg={
    // 给工厂调用的接口
    _init: function(attrs) {
        digg.init();
    },
    init: function() {
        digg.digglock = 0;
    },
    addDigg: function(group_id, post_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;


        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/like';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 201) {
                    $digg = $('#digg' + post_id);
                    var num = $digg.attr('rel');
                    num++;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.delDigg('+group_id+', '+post_id+', \'read\');" class="act"><svg class="icon"><use xlink:href="#icon-xihuan-white-copy"></use></svg><font class="ds"> '+num+'</font>人喜欢</a>');
                    } else {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.delDigg('+group_id+', '+post_id+');"><svg class="icon"><use xlink:href="#icon-xihuan-red"></use></svg><font> '+num+'</font></a>');
                    }
                } else {
                    alert(res.message);
                }

                digg.digglock = 0;
            }
        });

    },
    delDigg: function(group_id, post_id, page) {
        if (digg.digglock == 1) {
            return false;
        }
        digg.digglock = 1;

        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/like';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            error: function(xml) {},
            success: function(res, data, xml) {
                if (xml.status == 204) {
                    $digg = $('#digg' + post_id);
                    var num = $digg.attr('rel');
                    num--;
                    $digg.attr('rel', num);
                    if (page == 'read') {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.addDigg('+group_id+', '+post_id+', \'read\');"><svg class="icon"><use xlink:href="#icon-xihuan-white"></use></svg><font class="ds"> '+num+'</font>人喜欢</a>');
                    } else {
                        $('#digg' + post_id).html('<a href="javascript:;" onclick="digg.addDigg('+group_id+', '+post_id+');"><svg class="icon"><use xlink:href="#icon-xihuan-white"></use></svg><font> '+num+'</font></a>');
                    }
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
    addCollect: function(group_id, post_id, page) {
        // 未登录弹出弹出层
        if (MID == 0) {
            window.location.href = '/passport/login';
            return;
        }

        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;

        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/collection';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + post_id);
                var num = $collect.attr('rel');
                num++;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.delCollect('+group_id+', '+post_id+', \'read\');" class="act"><svg class="icon"><use xlink:href="#icon-shoucang-copy"></use></svg><font class="cs"> '+num+'</font>人收藏</a>');
                } else {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.delCollect('+group_id+', '+post_id+');" class="act"><svg class="icon"><use xlink:href="#icon-shoucang-copy"></use></svg> 已收藏</a>');
                }

                collect.collectlock = 0;
            }
        });

    },
    delCollect: function(group_id, post_id, page) {
        if (collect.collectlock == 1) {
            return false;
        }
        collect.collectlock = 1;
        var url = '/api/v2/groups/' + group_id + '/posts/' + post_id + '/collection';
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res, data, xml) {
                $collect = $('#collect' + post_id);
                var num = $collect.attr('rel');
                num--;
                $collect.attr('rel', num);
                if (page == 'read') {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.addCollect('+group_id+', '+post_id+', \'read\');"><svg class="icon"><use xlink:href="#icon-shoucang-copy1"></use></svg><font class="cs"> '+num+'</font>人收藏</a>');
                } else {
                    $('#collect' + post_id).html('<a href="javascript:;" onclick="collect.addCollect('+group_id+', '+post_id+');"><svg class="icon"><use xlink:href="#icon-shoucang-copy1"></use></svg> 收藏</a>');
                }
                collect.collectlock = 0;
            }
        });
    }
};