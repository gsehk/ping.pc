@extends('pcview::layouts.default')

@section('bgcolor')style="background-color:#f3f6f7"@endsection

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/account.css')}}"/>
@endsection

@section('content')

<div class="account_container">
<div class="account_wrap">

{{-- 左侧导航 --}}
@include('pcview::account.sidebar')

<div class="account_r">
    <div class="account_c_c">
		<!-- 标签管理 -->
        <div class="account_tab">
            <!-- label -->
            <div class="perfext_title">
                <p>选择标签</p>
            </div>
            <!-- /label -->
            <!-- list -->
            @foreach ($tags as $tag)
				<div class="perfect_row">
	                <label>{{$tag->name}}</label>
	                <ul class="perfect_label_list" id="J-tags">
	                @foreach ($tag->tags as $item)
	                	<li data-id="{{$item->id}}">{{$item->name}}</li>
	                @endforeach
	                </ul>
	            </div>
            @endforeach
            <!-- /list -->
            <!-- select -->
            <div class="perfect_selected">
                <label>最多可选
                    <span class="total">5</span>个标签，已选择
                    <span class="cur_count">0</span>个</label>
                <ul class="perfect_selected_list">
                    <li>建筑师<i class="icon close"></i></li>
                    <li>旅行家<i class="icon close"></i></li>
                    <li>运动达人<i class="icon close"></i></li>
                </ul>
            </div>
            <!-- /select -->
            <!-- btn -->
            <div class="perfect_btns">
                <a href="javascript:;" class="perfect_btn save" id="save">保存</a>
            </div>
            <!-- /btn -->
        </div>
        <!-- /标签管理 -->
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('zhiyicx/plus-component-pc/js/module.account.js')}}"></script>
<script>
$('#J-tags li').on('click', function(e){
	var _this = $(this);
	var tag_id = $(this).data('id');
	if (_this.hasClass('active')) {
		$.ajax({
	        url: '/api/v2/user/tags/'+tag_id,
	        type: 'DELETE',
	        dataType: 'json',
	        error: function(xml) {
	            noticebox('操作失败', 0, 'refresh');
	        },
	        success: function(res) {
	        	_this.addClass('active');
	        }
	    });
	} else {
		$.ajax({
	        url: '/api/v2/user/tags/'+tag_id,
	        type: 'PUT',
	        dataType: 'json',
	        error: function(xml) {
	            noticebox('操作失败', 0, 'refresh');
	        },
	        success: function(res) {
	        	_this.removeClass('active');
	        }
	    });
	}
});
</script>
@endsection