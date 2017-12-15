@section('title')圈子成员@endsection

@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('zhiyicx/plus-component-pc/css/group.css') }}">
@endsection

@section('content')
<div class="p-member p-member1">
    <div class="g-mn">
        <div class="g-hd f-mb30">
            <span class="f-fs5">圈子公告</span>
            <a class="f-fr s-fc" href="javascript:history.go(-1);">返回</a>
        </div>
        <div class="g-bd">
	        <div>
	            <div class="f-mt20 f-fs4">圈主</div>
	            <dl class="m-row">
	                <dt><img src="{{$group->user->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="50"></dt>
	                <dd>{{$group->user->name}}</dd>
	            </dl>
	        </div>
	        <div>
	            <div class="f-mt20 f-fs4">管理员</div>
	            @if (!$manager->isEmpty())
	            @foreach ($manager as $manage)
	                <dl class="m-row">
	                    <dt><img src="{{$manage->user->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png')}}" width="50"></dt>
	                    <dd><div>{{$manage->user->name}}</div></dd>
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
	                    <dd><div>{{$member->user->name}}</div></dd>
	                </dl>
	            @endforeach
	            @else
	                <p class="no-member">暂无成员</p>
	            @endif
	        </div>
        </div>
    </div>
</div>
@endsection