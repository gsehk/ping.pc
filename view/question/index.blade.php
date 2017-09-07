
@extends('pcview::layouts.default')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/profile.css') }}"/>
@endsection

@section('content')

<div class="left_container">
	<div class="nav-box">
		nav
	</div>
	<div class="question-body">
		body
	</div>
</div>

<div class="right_container">
    @include('pcview::widgets.hotissues')
    @include('pcview::widgets.answerank')
</div>

@endsection