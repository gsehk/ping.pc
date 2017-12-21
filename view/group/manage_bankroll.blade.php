
@section('title') {{ $group->name }}-圈子收益 @endsection

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
                <a href="{{ route('pc:groupbankroll', ['group_id'=>$group->id]) }}"><li class="cur">圈子收益</li></a>
                <a href="{{ route('pc:groupmember', ['group_id'=>$group->id]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group->id]) }}"><li>举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav">
                <ul class="f-cb" id="J-tab">
                    <li class="cur" type="all">圈子财务</li>
                    <li type="pinned">置顶收益</li>
                    <li type="join">成员费</li>
                </ul>
            </div>
            <div class="m-hd">
                <div class="m-income all-income">
                    <span>{{$group->join_income_count+$group->pinned_income_count}}</span>
                    <div class="s-fc4 f-fs2">账户余额（金币）</div>
                </div>
                <div class="f-dn m-income pinned-income">
                    <span>{{$group->pinned_income_count or 0}}</span>
                    <div class="s-fc4 f-fs2">置顶收益（金币） 共置顶了
                    <font color="#3CA967">{{$group->posts_count}}</font> 条帖子</div>
                </div>
                <div class="f-dn m-income join-income">
                    <span>{{$group->join_income_count or 0}}</span>
                    <div class="s-fc4 f-fs2">成员费（金币） 共
                    <font color="#3CA967">{{$group->users_count}}</font> 个付费成员</div>
                </div>
            </div>
            <div class="m-ct">
                <div class="u-tt f-mb20">
                    <span>交易记录</span>
                    <div class="m-filter f-fr">
                        <input class="t-filter" type="text" placeholder="请选择日期">
                    </div>
                </div>
                <div id="incomes-box"> </div>
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
        container: '#incomes-box',
        loading: '#incomes-box',
        url: '/group/incomes',
        params: {limit: 15, group_id: {{$group->id}} }
    });
}, 200);

laydate.render({
    elem: '.t-filter',
    done: function(value){
        $('#incomes-box').html('');
        scroll.init({
            container: '#incomes-box',
            loading: '#incomes-box',
            url: '/group/incomes',
            params: {limit: 15, group_id: {{$group->id}}, start: value }
        });
    }
});

$('#J-tab li').on('click', function(){
    var type = $(this).attr('type');
        $('#incomes-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
        $('.m-income').hide(); $('.'+type+'-income').show();
    var params = {
        limit: 15,
        type: type,
        group_id: {{$group->id}},
    }
    scroll.init({
        container: '#incomes-box',
        loading: '#incomes-box',
        url: '/group/incomes',
        params: params
    });

    (type=='all') ? $('.m-filter').show() : $('.m-filter').hide();
});
</script>
@endsection