@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
    <div class="dy_bg fans_bg">
        <div class="dy_cont list_bg">
            <ul class="list_ul">
                <li><a href="{{ route('pc:rank',['mold'=>1]) }}" class="fs-16 @if($mold == 1) a_border @endif">用户排行榜</a></li>
                <li><a href="{{ route('pc:rank',['mold'=>2]) }}" class="fs-16 @if($mold == 2) a_border @endif">问答排行榜</a></li>
                <li><a href="{{ route('pc:rank',['mold'=>3]) }}" class="fs-16 @if($mold == 3) a_border @endif">动态排行榜</a></li>
                <li><a href="{{ route('pc:rank',['mold'=>4]) }}" class="fs-16 @if($mold == 4) a_border @endif">资讯排行榜</a></li>
            </ul>
            @if($mold == 1)
                <div class="fans_div">
                    @component('pcview::rank.rank', ['title' => '粉丝排行榜', 'genre' => 'follower', 'post' => $follower, 'tabName' => '粉丝数'])
                    @endcomponent
                </div>
            @endif
        </div>
@endsection

@section('scripts')
            <link href="{{ $routes['resource'] }}/css/rank.css" rel="stylesheet">
@endsection
