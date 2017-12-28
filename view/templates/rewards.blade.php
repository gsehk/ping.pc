@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="reward_popups" @if($rewards->count() < 6) style="height:auto;" @endif>
    <p class="reward_title ucolor font14">打赏列表</p>
    <div class="reward_popups_con">
        <ul class="reward_list" id="J-reward-list">
            @foreach($rewards as $reward)
                <li>
                    <a class="u-avatar" href="{{ route('pc:mine', $reward->user->id) }}">
                        <img src="{{ getAvatar($reward->user, 40) }}" class="lazy avatar" width="40"/>
                        @if($reward->user->verified)
                        <img class="role-icon" src="{{ $reward->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                        @endif
                    </a>
                    <a href="{{ route('pc:mine', $reward->user->id) }}" class="uname">{{ $reward->user->name }}</a>
                    <font color="#aaa">打赏了 {{ $app }}</font>
                </li>
            @endforeach
        </ul>
    </div>
</div>