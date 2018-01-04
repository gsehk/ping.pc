@section('title')发布帖子@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-publish">
    <div class="g-mn left_container">
        <div class="m-form">
            <div class="formitm">
                <input class="ipt f-fs5" id="title" name="title" type="text"  placeholder="请在此输入20字以内的标题"/>
            </div>
            <div class="formitm">
                @include('pcview::widgets.markdown', ['place' => '请输入帖子内容', 'content'=>$content ?? ''])
            </div>
            @if ($group->allow_feed)
            <div class="formitm">
                <input class="iptck" type="checkbox" name="sync_feed" value="1"><span>同步分享至动态</span>
            </div>
            @endif
            <div class="f-tac">
                {{-- <button class="btn btn-default btn-lg" id="" type="button">存草稿</button> --}}
                <button class="btn btn-primary btn-lg" id="J-publish-post" type="button">发 布</button>
            </div>
        </div>
    </div>
    <div class="g-side right_container">
        {{-- 热门圈子 --}}
        @include('pcview::widgets.hotgroups')
    </div>
</div>
@endsection
<script src="{{ asset('zhiyicx/plus-component-pc/js/axios.min.js')}}"></script>
<script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js') }}"></script>
@section('scripts')
<script>
axios.defaults.baseURL = TS.API;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

$('#J-publish-post').on('click', function(e){
    var POST_URL = '/plus-group/groups/{{$group->id}}/posts';
    var isSync = $("input:checkbox:checked").val();
    var args = {
        'title': $('#title').val(),
        'body': editor.value(),
        'summary': '',
        'images': [],
    };
    var images = args.body.match(/\((\d+)\)/g);
    _.forEach(images, function(v, k) {
        var id = v.match(/\d+/g);
        args.images.push(id[0]);
    });

    var bodyStr = args.body.replace(/\@\!\[(.*)\]\((\d+)\)/gi, "");
    if (bodyStr) {
        args.summary = bodyStr.replace(/(\[(.*)\]\((.*)\))|(\n)|([\\\`\*\_\[\]\#\+\-\!\>\~])/g, "");
        if (getLength(args.summary) > 190) {
            args.summary = args.summary.substring(0, 190);
        }
    }
    if (!args.title || getLength(args.title) > 20) {
        noticebox('请输入20字以内的标题', 0);return;
    }

    if (!args.body) {
        noticebox('请输入帖子内容', 0);return;
    }
    if (isSync !== undefined) {
        args.sync_feed = isSync;
        args.feed_from = 1;
    }
    var _this = this;
    if (_this.lockStatus == 1) {
        return;
    }
    _this.lockStatus = 1;
    axios.post(POST_URL, args)
    .then(function (response) {
        noticebox('发布成功', 1, '/group/{{ $group->id }}/post/' + response.data.post.id);
        _this.lockStatus = 0;
    })
    .catch(function (error) {
        showError(error.response.data);
        _this.lockStatus = 0;
    });
});
</script>
@endsection