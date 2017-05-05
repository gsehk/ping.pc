
var args = {
  data: {},
  set: function(name, value) {
    this.data[name] = value;
    return this;
  },
  get: function () {
    return this.data;
  }
};


var getImgInfo = function(event){
    var $this = $(this);
    var callback = event.data.callback;
    var f = document.getElementById(event.data.id).files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        var data = e.target.result;
        //加载图片获取图片真实宽度和高度
        var image = new Image();
        image.onload=function(){
            var width = image.width;
            var height = image.height;
            var size = f.size;
            callback(width, height, f, $this);
        };
        if ($this.data('preview')) {
          $($this.data('preview')).attr('src', data);
        }
        image.src= data;
   };
   reader.readAsDataURL(f);
};
var ajaxFileUpload = function(width, height, f, obj) {

    var args = {
        width  : width,
        height : height,
        mime_type : f.type,
        origin_filename : f.name,
        _token : obj.data('token'),
        hash   : CryptoJS.MD5(f.name).toString(),
    };
    var formData = new FormData($(obj.data('form'))[0]); 
    // 创建存储任务
    $.ajax({  
        url: '/api/v1/storages/task' ,  
        type: 'POST',  
        data: args,
        beforeSend: function (xhr) {
  　　　　  xhr.setRequestHeader('Authorization', '67bbd394939f52a0be3a6ff6e1845811');
  　　　}, 
        success: function (res) {  
            if (res.data.uri) {
              if (res.data.options) {
                for(var i in res.data.options){
                  formData.append(i, res.data.options[i]);
                }
              }
              // 上传文件
              $.ajax({
                  url: res.data.uri,  
                  type: res.data.method,  
                  data: formData,  
                  async: false,  
                  cache: false,  
                  contentType: false,  
                  processData: false,   
                  beforeSend: function (xhr) {
            　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
            　　　}, 
                  success: function (data) { 
                  // 上传通知 
                    $.ajax({
                        url: '/api/v1/storages/task/'+res.data.storage_task_id,  
                        type: 'PATCH',
                        beforeSend: function (xhr) {
                  　　　　  xhr.setRequestHeader('Authorization', res.data.headers.Authorization);
                  　　　}, 
                        success: function(response){

                            $(obj.data('input')).val(res.data.storage_task_id);
                        }
                    });
                  } 
              }); 
            }else{
                $(obj.data('input')).val(res.data.storage_task_id);
            }
        }
    });
};
