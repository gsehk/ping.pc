{{-- 举报详情 --}}
@section('title')圈子-举报管理@endsection

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
                <a href="{{ route('pc:groupmember', ['group_id'=>$group->id]) }}"><li>成员管理</li></a>
                <a href="{{ route('pc:groupbankroll', ['group_id'=>$group->id]) }}"><li>财务管理</li></a>
                <a href="{{ route('pc:groupreport', ['group_id'=>$group->id]) }}"><li class="cur">举报管理</li></a>
            </ul>
        </div>
        <div class="g-mn">
            <nav class="m-crumb m-crumb-arr">
                <ul class="f-cb s-fc2 f-ib">
                    <li><a href="#">圈子财务</a></li>
                    <li>管理详情</li>
                </ul>
                <a class="u-back" href="javascript:history.go(-1);">返回</a>
            </nav>
            <div class="m-hd">
                <div class="m-income">
                    <span>111</span>
                    <div class="s-fc4 f-fs2">交易成功</div>
                </div>
            </div>
            <div class="m-ct">
                <div class="m-item">
                    <label class="u-lab">收款人</label>
                    <p class="u-ct">
                        <img class="avatar" src="{{asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="32">&nbsp;&nbsp;&nbsp;李二狗
                    </p>
                </div>
                <div class="m-item">
                    <label class="u-lab">交易说明</label>
                    <p class="u-ct">
                        举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容举报内容
                    </p>
                </div>
                <div class="m-item f-mb30">
                    <label class="u-lab">创建时间</label>
                    <p class="u-ct">
                        2017-12-12 10:00
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection