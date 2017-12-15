{{-- 成员管理 --}}
@section('title')创建圈子@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-member p-group">
    <div class="g-bd f-cb">
        <div class="g-sd">
            <ul>
                <a href="{{ route('pc:groupedit', ['group_id'=>$group->id]) }}"><li>圈子资料</li></a>
                <a href="{{ route('pc:groupmember', ['group_id'=>$group->id]) }}"><li class="cur">成员管理</li></a>
                <a href="{{ route('pc:groupbankroll', ['group_id'=>$group->id]) }}"><li>财务管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group->id]) }}"><li>举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <div class="m-nav">
                <ul class="f-cb" id="J-tab">
                    <li class="cur" type="member">全部成员</li>
                    <li type="audit">待审核</li>
                    <li type="blacklist">黑名单</li>
                </ul>
                <div class="m-sch f-fr">
                    <input class="u-schipt" type="text" placeholder="输入关键词搜索">
                    <a class="u-schico" href="javascript:;"><svg class="icon s-fc"><use xlink:href="#icon-search"></use></svg></a>
               </div>
            </div>
            <div class="g-body">
            <div>
                <div class="f-mt20 f-fs4">圈主</div>
                <dl class="m-row">
                    <dt><img src="{{$group->founder->user->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="50"></dt>
                    <dd>{{$group->founder->user->name}}</dd>
                </dl>
            </div>
            <div>
                <div class="f-mt20 f-fs4">管理员</div>
                @if (!$manager->isEmpty())
                @foreach ($manager as $manage)
                    <dl class="m-row">
                        <dt><img src="{{$manage->user->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="50"></dt>
                        <dd><div>{{$manage->user->name}}</div>
                            <div class="u-opt">
                                <span>管理</span>
                                <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                                @if ($group->joined->role == 'administrator')
                                    <ul class="u-menu f-dn">
                                        <a href="javascript:;"><li>撤销管理员</li></a>
                                        <a href="javascript:;"><li>加入黑名单</li></a>
                                        <a href="javascript:;"><li>踢出圈子</li></a>
                                    </ul>
                                @endif
                            </div>
                        </dd>
                    </dl>
                @endforeach
                @else
                    <p class="no-member">暂无成员</p>
                @endif
            </div>
            <div>
                <div class="f-mt20 f-fs4">一般成员</div>
                <div id="member-box"> </div>
                @if (!$members->isEmpty())
                @foreach ($members as $member)
                    <dl class="m-row">
                        <dt><img src="{{$member->user->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="50"></dt>
                        <dd><div>{{$member->user->name}}</div>
                            <div class="u-opt">
                                <span>管理</span>
                                <svg class="icon f-fs2"><use xlink:href="#icon-setting"></use></svg>
                                <ul class="u-menu f-dn">
                                    @if ($group->joined->role == 'administrator')
                                    <a href="javascript:;" onclick="MAG.set({{$group->id}}, {{$member->user_id}}, 1);"><li>设为管理员</li></a>
                                    @endif
                                    <a href="javascript:;" onclick="MAG.black({{$group->id}}, {{$member->user_id}}, 1);"><li>加入黑名单</li></a>
                                    <a href="javascript:;" onclick="MAG.delete({{$group->id}}, {{$member->user_id}});"><li>踢出圈子</li></a>
                                </ul>
                            </div>
                        </dd>
                    </dl>
                @endforeach
                @else
                    <p class="no-member">暂无成员</p>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@section('scripts')
<script>
axios.defaults.baseURL = TS.API;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + TS.TOKEN;
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');

$('.u-opt svg').on('click', function(){
    $(this).next("ul").toggle();
});

var MAG = {
    /**
     * 设置圈子管理员
     * @param int gid
     * @param int uid
     * @param int type [1-设置 0-移除]
     */
    set: function(gid, uid, type){
        var params = {
            url: ' /plus-group/groups/'+gid+'/managers/'+uid,
            method: type ? 'PUT' : 'DELETE',
        }
        axios({
            method: params.method,
            url: params.url,
        })
        .then(function (response) {
            noticebox('操作成功', 1);
        });
    },
    /**
     * 设置圈子黑名单
     * @param int gid
     * @param int uid
     * @param int type [1-加入 0-移除]
     */
    black: function(gid, uid, type){
        var params = {
            url: '/plus-group/groups/'+gid+'/blacklist/'+uid,
            method:type ? 'PUT' : 'DELETE',
        }
        axios({
            method: params.method,
            url: params.url,
        })
        .then(function (response) {
            noticebox('操作成功', 1);
        });
    },
    /**
     * 审核圈子加入请求
     * @param  int gid
     * @param  int uid
     */
    audit: function(gid, uid){
        var URL = '/plus-group/groups/'+gid+'/members/'+uid+'/audit';
        axios.patch( URL )
            .then(function (response) {
                noticebox('操作成功', 1);
            });
    },
    /**
     * 移除圈子成员
     * @param  int gid
     * @param  int uid
     */
    delete: function(gid, uid){
        var URL = '/plus-group/groups/'+gid+'/members/'+uid;
        axios.delete( URL )
            .then(function (response) {
                noticebox('操作成功', 1);
            });
    }
}

$('#J-tab li').on('click', function(){
    var type = $(this).attr('type');
        $('#member-box').html('');
        $('#J-tab li').removeClass('cur'); $(this).addClass('cur');
    var params = {
        limit: 15,
        type: type,
        group_id: {{$group->id}},
    }
    scroll.init({
        container: '#member-box',
        loading: '#member-box',
        url: '/group/get-member',
        params: params
    });

});
</script>
@endsection