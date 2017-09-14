{{-- 个人中心导航栏 --}}
<div class="profile_nav clearfix">
    @if ($TS->id == $user->id)
        <ul class="profile_nav_list clearfix">
            <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user['id']) }}">主页</a></li>

            <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup') }}">圈子</a></li>

            <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews') }}">资讯</a></li>

            <li @if($current == 'collect') class="active" @endif><a href="{{ route('pc:profilecollect') }}">收藏</a></li>
        </ul>

        <a class="btn btn-primary contribute-btn" href="{{ route('pc:newsrelease') }}">
            <svg class="icon"><use xlink:href="#icon-feiji"></use></svg>投稿
        </a>
    @else
        <ul class="profile_nav_list clearfix">
            <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user['id']) }}">TA的主页</a></li>

            <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup', $user->id) }}">TA的圈子</a></li>
        </ul>

        <div class="follow-box">
            @if ($user->hasFollower == 0)
                <div id="follow" status="0" class="tcolor">+关注</div>
            @else
                <div id="follow" status="1" class="followed">已关注</div>
            @endif
        </div>
    @endif
</div>