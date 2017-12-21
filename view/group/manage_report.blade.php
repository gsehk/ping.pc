
@section('title') {{ $group->name }}-举报管理 @endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-bankroll p-group">
    <div class="g-bd f-cb">
        <div class="g-sd">
            <ul>
                <a href="{{ route('pc:groupedit', ['group_id'=>$group->id]) }}"><li>圈子资料</li></a>
                <a href="{{ route('pc:groupbankroll', ['group_id'=>$group->id]) }}"><li>圈子收益</li></a>
                <a href="{{ route('pc:groupmember', ['group_id'=>$group->id]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group->id]) }}"><li class="cur">举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav  f-mb20">
                <ul class="f-cb" id="J-tab">
                    <li class="cur">全部</li>
                    <li status="0">未处理</li>
                    <li status="1">已处理</li>
                    <li status="2">已驳回</li>
                </ul>
                <div class="m-filter f-fr">
                    <input class="t-filter" type="text" placeholder="请选择日期">
                </div>
            </div>
            <div class="m-ct">
                <div id="report-box"> </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('zhiyicx/plus-component-pc/layer/laydate/laydate.js')}}"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@section('scripts')
<script>
axios.defaults.baseURL = TS.API;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

setTimeout(function() {
    scroll.init({
        container: '#report-box',
        loading: '#report-box',
        url: '/group/report',
        params: {limit: 15, group_id: {{$group->id}} }
    });
}, 200);

laydate.render({
    elem: '.t-filter',
    done: function(value){
        $('#report-box').html('');
        scroll.init({
            container: '#report-box',
            loading: '#report-box',
            url: '/group/report',
            params: {limit: 15, group_id: {{$group->id}}, start: value }
        });
    }
});

$('#J-tab li').on('click', function(){
    var status = $(this).attr('status');
        $('#report-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
    var params = {
        limit: 15,
        group_id: {{$group->id}},
    }
    if (status != undefined) {
        params.status = status;
    }
    scroll.init({
        container: '#report-box',
        loading: '#report-box',
        url: '/group/report',
        params: params
    });

});
</script>
@endsection