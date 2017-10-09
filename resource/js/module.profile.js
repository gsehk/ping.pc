
/**
 * 微博操作
 * @type {Object}
 */
var weibo = {
    delFeed : function(id) {
        var url = '/api/v2/feeds/' + id;
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
        type = type ? type : 'feeds';
        if (type == 'feeds') {
            var url = '/api/v2/feeds/'+id+'/pinneds';
            pinneds(url);
        }
    },
    addComment: function (row_id, type) {
        var url = '/api/v2/feeds/' + row_id + '/comments';
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

var news = {
    pinneds: function(id) {
        var url = '/api/v2/news/'+id+'/pinneds';
        pinneds(url);
    },
    addComment: function(row_id, type) {
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

    // 显示跳转详情文字
    $('body').on("mouseover mouseout", '.date', function(event){
        if(event.type == "mouseover"){
          var width = $(this).find('span').first().width();
            width = width < 60 ? 60 : width;
          $(this).find('span').first().hide();
          $(this).find('span').last().css({display:'inline-block', width: width});
        }else if(event.type == "mouseout"){
          $(this).find('span').first().show();
          $(this).find('span').last().hide();
        }
    });
})