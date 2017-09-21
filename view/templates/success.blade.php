{{--@section('title')--}}
    {{--资讯--}}
{{--@endsection--}}

@extends('pcview::layouts.default')

@section('content')
    <div class="success">
        <div class="content">
            <div class="success-message">{{$message or '操作成功'}}</div>
            <div class="success-content">{{$content or '操作成功！'}}</div>
            <a href="{{$url}}" class="success-button">返回</a>
        </div>
    </div>
@endsection
