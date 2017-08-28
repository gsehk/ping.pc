@section('title')
动态
@endsection

@extends('pcview::layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
    <!-- left -->
    @component('pcview::layouts.partials.leftmenu')
    @endcomponent
    <!-- middle -->
    <div class="feed_cont">
        <div class="feed_content">
            <div class="feed_menu">
                <a href="javascript:;" data-type="users" class="fs-16 selected">动态</a>
            </div>
            <div id="feeds_list"></div>
        </div>
    </div>

    <!-- right -->
    <div class="right_container">
        <!-- checkin -->
        @include('pcview::widgets.checkin')

        <!-- recommend users -->
        @include('pcview::widgets.recusers')
    </div>
@endsection

@section('scripts')

<link href="{{ $routes['resource'] }}/css/feed.css" rel="stylesheet">
<script src="{{ $routes['resource'] }}/js/module.weibo.js"></script>
<script src="{{ $routes['resource'] }}/js/jquery.uploadify.js"></script>
<script src="{{ $routes['resource'] }}/js/md5.min.js"></script>
<script type="text/javascript">
    // 加载微博
    var params = {
        type: 'users'
    };

    setTimeout(function() {
        scroll.init({
            container: '#feeds_list',
            loading: '.feed_content',
            url: '/feeds',
            params: params
        });
    }, 300);
</script>
@endsection
