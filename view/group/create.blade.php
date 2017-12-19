@section('title')创建圈子@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-addgroup">
    <div class="g-mn">
        <h1 class="u-tt">创建圈子</h1>
        <div class="m-form">
            <div class="formitm">
                <input class="chools-cover" id="J-upload-cover" type="file" name="file">
                <img class="cover" id="J-preview-cover" src="{{ asset('zhiyicx/plus-component-pc/images/pic_upload.png') }}">
                <input id="avatar_id" type="hidden" name="avatar" value="" />
            </div>
            <div class="formitm">
                <label class="lab">圈子名称</label>
                <input class="ipt" name="name" type="text"  placeholder="最多 20 个字"/>
            </div>
            <div class="formitm">
                <label class="lab">圈子简介</label>
                <textarea class="txt" name="summary" rows="4" placeholder="最多 255 个字"></textarea>
            </div>
            <div class="formitm">
                <label class="lab">圈子分类</label>
                <div data-value="{{ $cates[0]['id'] }}" class="zy_select t_c gap12" id="categrey">
                    <span>推荐</span>
                    <ul>
                        @foreach ($cates as $key => $cate)
                            <li @if($key == 0) class="active" @endif data-value="{{$cate->id}}">{{$cate->name}}</li>
                        @endforeach
                    </ul>
                    <i></i>
                    <input id="cate" type="hidden" value="user" />
                </div>
            </div>
            <div class="formitm">
                <label class="lab">圈子标签</label>
                <div class="tags-box ipt">
                    <div class="choos-tags" id="J-tag-box">
                        @foreach ($tags as $tag)
                            <dl>
                                <dt>{{ $tag->name }}</dt>
                                @foreach ($tag->tags as $item)
                                    <dd data-id="{{$item->id}}">{{$item->name}}</dd>
                                @endforeach
                            </dl>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="formitm">
                <label class="lab">订阅模式</label>
                <div class="form-control f-ib" id="J-submodel">
                    <input class="regular-radio f-dn" id="radio-free" name="modes" type="radio" value="1" checked />
                    <label class="radio" for="radio-free"></label>免费&nbsp;&nbsp;
                    <input class="regular-radio f-dn" id="radio-pay" name="modes" type="radio" value="2"/>
                    <label class="radio" for="radio-pay"></label>付费
                </div>
            </div>
            <div class="formitm auth-box">
                <div class="j-sub0">
                    <div class="form-control f-mb20">
                        <input class="regular-radio f-dn" id="radio-open" name="mode" type="radio" value="public" checked />
                        <label class="radio" for="radio-open"></label>公开圈子<span class="f-ml20 s-fc4">加入圈子即可发帖</span>
                    </div>
                    <div class="form-control">
                        <input class="regular-radio f-dn" id="radio-private" name="mode" type="radio" value="private" />
                        <label class="radio" for="radio-private"></label>封闭圈子<span class="f-ml20 s-fc4">未通过加入申请的人不能进入圈子</span>
                    </div>
                </div>
                <div class="form-control f-dn j-sub1">
                    <label class="lab">设置入圈金额</label>
                    <input class="iptline" name="money" type="text"/>&nbsp;&nbsp;<span class="s-fc4">金币</span>
                </div>
            </div>
            <div class="formitm">
                <label class="lab">圈子位置</label>
                <input class="ipt" name="location" type="text" placeholder="输入所在地区" />
            </div>
            <div class="formitm">
                <label class="lab">圈子公告</label>
                <textarea class="txt" name="notice" rows="6" placeholder="编辑自己的圈子公告或规则（选填）"></textarea>
            </div>
            <div class="formitm">
                <label class="lab">&nbsp;</label>
                <input class="iptck" type="checkbox" name="protocol" checked><span>我已阅读并遵守ThinkSNS+的圈子创建协议</span>
            </div>
            <p class="tooltips">提交后，我们将在2个工作日内给予反馈，谢谢合作！</p>
            <div class="f-tac">
                <button class="btn btn-primary btn-lg" id="J-create-group" type="button">提 交</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
@section('scripts')
<script>
axios.defaults.baseURL = TS.API;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

$('#J-submodel label').on('click', function(e){
    var val = $('#'+$(this).attr('for')).val();
    if (val == '2') {
        $('.j-sub0').hide();
        $('.j-sub1').show();
    } else {
        $('.j-sub0').show();
        $('.j-sub1').hide();
    }
});

var selBox = $('.tags-box');
$('#J-tag-box dd').on('click', function(e){
    e.stopPropagation();
    var tid = $(this).data('id');
    var name = $(this).text();
    if (selBox.find('span').hasClass('tid'+tid)) {
        noticebox('标签已存在', 0); return;
    }

    if (selBox.find('span').length > 4) {
        noticebox('标签最多五个', 0); return;
    }
    selBox.append('<span class="tid'+tid+'" data-id="'+tid+'">'+name+'</span>');
});
selBox.on('click', 'span', function(){
    $(this).remove();
});

$('#J-upload-cover').on('change', function(e){
    var file = e.target.files[0];
    var a = new FileReader();
    a.onload = function(e) {
        var data = e.target.result;
        $('#J-preview-cover').attr('src', data);
    };
    a.readAsDataURL(file);
    /*fileUpload.init(file, function(image, f, file_id){
        $('#avatar_id').val(file_id);
        $('#J-preview-cover').attr('src', TS.API+'/files/'+file_id+'?w=230&h=163');
    });*/
});

$('#J-create-group').on('click', function(){
    var protocol = $('[name="protocol"]:checked').val();
    var categrey = $('#categrey').data('value');
    var modeType = $('[name="modes"]:checked').val();
    var POST_URL = '/plus-group/categories/' + categrey + '/groups';
    var formData = new FormData();
        formData.append('avatar', $('#J-upload-cover')[0].files[0]);
        formData.append('name', $('[name="name"]').val());
        formData.append('summary', $('[name="summary"]').val());
        formData.append('notice', $('[name="notice"]').val());
        formData.append('location', $('[name="location"]').val());
        formData.append('latitude', '100');
        formData.append('longitude', '100');
        formData.append('geo_hash', '123');
        if (modeType == '1') {
            formData.append('mode', $('[name="mode"]:checked').val());
        } else {
            formData.append('mode', 'paid');
            formData.append('money', $('[name="money"]').val());
        }
        $('.tags-box span').each(function(){
            formData.append('tags[][id]', $(this).data('id'));
        });
        if (protocol !== undefined) {
            axios.post(POST_URL, formData)
            .then(function (response) {
                noticebox('发布成功，请等待审核', 1, '/group');
            })
            .catch(function (error) {
                showError(error.response.data);
            });
        }
});
</script>
@endsection