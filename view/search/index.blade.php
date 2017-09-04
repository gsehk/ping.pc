@section('title')
	搜索 {{ $keywords }}
@endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/search.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/feed.css') }}"/>
@endsection

@section('content')
    <div class="left_container clearfix">
    	<div class="search_menu">
    	</div>

    	<div id="content_list">
    	</div>
    </div>

    <div class="right_container">
        <!-- 推荐用户 -->
        @include('pcview::widgets.recusers')

        <!-- 近期热点 -->
        @include('pcview::widgets.hotnews')
    </div>
@endsection

@section('scripts')
<script type="text/javascript">

</script>
@endsection
