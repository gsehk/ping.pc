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
                    @component('pcview::rank.rank', ['title' => '财富达人排行榜', 'genre' => 'balance', 'post' => $balance])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '收入达人排行榜', 'genre' => 'income', 'post' => $income])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '社区签到排行榜', 'genre' => 'check', 'post' => $check])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '社区专家排行榜', 'genre' => 'experts', 'post' => $experts])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '问答达人排行榜', 'genre' => 'likes', 'post' => $likes, 'tabName' => '问答点赞量'])
                    @endcomponent
                </div>
            @elseif($mold == 2)     {{--解答排行榜--}}
                <div class="fans_div">
                    @component('pcview::rank.rank', ['title' => '今日解答排行榜', 'genre' => 'answers_day', 'post' => $answers_day, 'tabName' => '问答量'])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '一周解答排行榜', 'genre' => 'answers_week', 'post' => $answers_week, 'tabName' => '问答量'])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '本月解答排行榜', 'genre' => 'answers_month', 'post' => $answers_month, 'tabName' => '问答量'])
                    @endcomponent
                </div>
            @elseif($mold == 3)     {{--动态排行榜--}}
                <div class="fans_div">
                    @component('pcview::rank.rank', ['title' => '今日动态排行榜', 'genre' => 'feeds_day', 'post' => $feeds_day, 'tabName' => '点赞量'])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '一周动态排行榜', 'genre' => 'feeds_week', 'post' => $feeds_week, 'tabName' => '点赞量'])
                    @endcomponent
                    @component('pcview::rank.rank', ['title' => '本月动态排行榜', 'genre' => 'feeds_month', 'post' => $feeds_month, 'tabName' => '点赞量'])
                    @endcomponent
                </div>
            @elseif($mold == 4)     {{--资讯排行榜--}}
            <div class="fans_div">
                @component('pcview::rank.rank', ['title' => '今日资讯排行榜', 'genre' => 'news_day', 'post' => $news_day, 'tabName' => '浏览量'])
                @endcomponent
                @component('pcview::rank.rank', ['title' => '一周资讯排行榜', 'genre' => 'news_week', 'post' => $news_week, 'tabName' => '浏览量'])
                @endcomponent
                @component('pcview::rank.rank', ['title' => '本月资讯排行榜', 'genre' => 'news_month', 'post' => $news_month, 'tabName' => '浏览量'])
                @endcomponent
            </div>
            @endif
        </div>
@endsection

@section('scripts')
            <link href="{{ $routes['resource'] }}/css/rank.css" rel="stylesheet">
@endsection
