// var group={};
var grouped = {
    init: function(obj){
        checkLogin();
        this._this = obj;
        this.state = $(obj).attr('state');
        this.money = $(obj).attr('money');
        this.mode = $(obj).attr('mode');
        this.gid = $(obj).attr('id');
        this.count = parseInt($('#join-count-'+this.gid).text());
        if (parseInt(this.state)) {
            this.unjoined();
        } else {
            this.joined();
        }
    },
    joined:function(){
        var html = '';
        var _self = this;
        var _this = this._this;
        var url = TS.API+'/plus-group/groups/'+this.gid;
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;

        if (this.mode == 'paid') {
            html = '<div class="f-tac" style="padding:20px;">'+
                        '<h3 class="f-mb30">加入付费圈子</h3>'+
                        '<div><font color="red" size="4">'+this.money+'</font></div>'+
                        '<p>加入此圈子需要支付'+this.money+'金币，是否<br/>继续加入?</p>'+
                   '</div>';
        }
        if (this.mode == 'private') {
            html = '<div class="f-tac" style="padding:20px;">'+
                        '<h3 class="f-mb30">圈子审核</h3>'+
                        '<p>加入此圈子需要圈主或管理员审核，<br/>是否继续加入?</p>'+
                   '</div>';
        }
        if (html && html != '') {
            ly.confirm(html,'','',function(){
                $.ajax({
                    url: url,
                    type: 'PUT',
                    success: function(response) {
                        ly.close();
                        noticebox(response.message, 1);
                        _this.lockStatus = 0;
                    },
                    error: function(xhr){
                        ly.close();
                        _this.lockStatus = 0;
                        showError(xhr.responseJSON);
                    }
                })
            })
            _this.lockStatus = 0;
        } else {
            $.ajax({
                url: url,
                type: 'PUT',
                success: function(response) {
                    $(_this).text('已加入');
                    $(_this).attr('state', 1);
                    $(_this).addClass('joined');
                    $('#join-count-'+_self.gid).text(_self.count + 1);
                    _this.lockStatus = 0;
                },
                error: function(xhr){
                    ly.close();
                    _this.lockStatus = 0;
                    showError(xhr.responseJSON);
                }
            })
        }
    },
    unjoined:function(){
        var _self = this;
        var _this = this._this;
        var url = TS.API+'/plus-group/groups/'+this.gid+'/exit';
        if (_this.lockStatus == 1) {
            return;
        }
        _this.lockStatus = 1;

        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(response) {
                $(_this).text('+加入');
                $(_this).attr('state', 0);
                $(_this).removeClass('joined');
                $('#join-count-'+_self.gid).text(_self.count - 1);
                _this.lockStatus = 0;
            },
            error: function(xhr){
                ly.close();
                _this.lockStatus = 0;
                showError(xhr.responseJSON);
            }
        })
    }
}

var post = {};

/**
 * 上传后操作
 * @return void
 */
post.afterUpload = function(image, f, task_id) {
    var img = '<img class="imgloaded" onclick="post.showImg();" src="' + TS.SITE_URL + '/api/v2/files/' + task_id + '"/ tid="' + task_id + '" amount="">';
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
    checkLogin();
    var _this = this;
    if (_this.lockStatus == 1) {
        noticebox('请勿重复提交', 0);
        return;
    }

    var images = [];
    $('.feed_picture').find('img').each(function() {
        images.push({'id':$(this).attr('tid')});
    });

    var data = {
        //title: $('#post_title').val(),
        content: $('#feed_content').val(),
        images: images,
        group_post_mark: TS.MID + new Date().getTime(),
    };

    var strlen = getLength(data.content);
    var leftnums = initNums - strlen;
    if ((leftnums < 0 || leftnums == initNums) && data.images.length < 1) {
        noticebox('分享内容长度为1-' + initNums + '字', 0);
        return false;
    }
    _this.lockStatus = 1;
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
            _this.lockStatus = 0;
        },
        error: function(xhr){
            showError(xhr.responseJSON);
            _this.lockStatus = 0;
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
        var url ='/api/v2/plus-group/groups/' + group_id + '/posts/' + post_id;
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                $('#feed' + post_id).fadeOut();
                layer.closeAll();
            },
            error: function(xhr){
                layer.closeAll();
                showError(xhr.responseJSON);
            }
        });
    });
};

post.pinnedPost = function(post_id){
    var url = '/api/v2/plus-group/pinned/posts/'+post_id;
    pinneds(url);
};

post.reportPost = function(post_id){
    var url ='/api/v2/plus-group/reports/posts/'+post_id;
    reported(url);
};