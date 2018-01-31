@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
{{-- 个人中心头部个人信息 --}}
<div class="profile_top">
    <div class="profile_top_cover" style="background-image: url({{ $user->bg or asset('assets/pc/images/default_cover.png') }});background-repeat: no-repeat;background-size: cover;">
    </div>

    @if ($user->id == $TS->id)
        <input type="file" name="cover" style="display:none" id="cover">
        <span class="change_cover" onclick="$('#cover').click()">更换封面</span>
    @endif

    <div class="profile_top_info">
        <div class="profile_top_img relative fl">
            <a href="{{ route('pc:mine', $user->id) }}">
                <img class="round" src="{{ getAvatar($user, 160) }}"/>
                @if($user->verified)
                    <img class="role-icon" src="{{ $user->verified['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                @endif
            </a>
        </div>
        <div class="profile_top_info_d">
            <div class="profile_top_user">
                <a href="{{ route('pc:mine', $user->id) }}">{{ $user->name }}</a>
                <span>{{$user->location or '未知'}}</span>
            </div>
            <div class="profile_top_bio">{{ $user->bio or '这家伙很懒，什么都没留下'}}</div>
            <div class="profile_top_tags">
                @foreach ($user->tags as $tag)
                    <span>{{$tag->name}}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 个人中心导航栏 --}}
    <div class="profile_nav clearfix">
        @if ($TS->id == $user->id)
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user->id) }}">主页</a></li>

                <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup') }}">圈子</a></li>

                 <li @if($current == 'question') class="active" @endif><a href="{{ route('pc:profilequestion') }}">问答</a></li>

                <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews') }}">资讯</a></li>

                <li @if($current == 'collect') class="active" @endif><a href="{{ route('pc:profilecollect') }}">收藏</a></li>
            </ul>

            <a class="btn btn-primary contribute-btn" href="{{ route('pc:newsrelease') }}">
                <svg class="icon"><use xlink:href="#icon-publish"></use></svg>投稿
            </a>
        @else
            <ul class="profile_nav_list clearfix">
                <li @if($current == 'feeds') class="active" @endif><a href="{{ route('pc:mine', $user->id) }}">TA的主页</a></li>

                <li @if($current == 'group') class="active" @endif><a href="{{ route('pc:profilegroup', $user->id) }}">TA的圈子</a></li>

                <li @if($current == 'news') class="active" @endif><a href="{{ route('pc:profilenews', $user->id) }}">TA的文章</a></li>

                <li @if($current == 'question') class="active" @endif><a href="{{ route('pc:profilequestion', $user->id) }}">TA的问答</a></li>
            </ul>
            <a class="btn profile-btn mcolor" href="javascript:;" onclick="easemob.createCon({{ $user->id }})">
                <svg class="icon"><use xlink:href="#icon-messaged"></use></svg>聊天
            </a>
            @if ($user->follower)
                <a class="btn profile-btn mcolor" id="follow" status="1" uid="{{$user->id}}" href="javascript:;">
                    <svg class="icon hide"><use xlink:href="#icon-add"></use></svg><span>已关注</span>
                </a>
            @else
                <a class="btn profile-btn mcolor" id="follow" status="0" uid="{{$user->id}}" href="javascript:;">
                    <svg class="icon"><use xlink:href="#icon-add"></use></svg><span>关注</span>
                </a>
            @endif

            <a class="btn reward-btn profile-btn mcolor" href="javascript:;" onclick="rewarded.show({{$user->id}}, 'user')">
                <svg class="icon"><use xlink:href="#icon-money"></use></svg>打赏
            </a>
        @endif
    </div>
</div>