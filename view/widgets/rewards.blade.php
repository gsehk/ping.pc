<div class="reward_cont">
    <p><button class="btn btn-warning btn-lg" onclick="rewarded.show({{ $rewards_id }}, '{{ $rewards_type }}')">打 赏</button></p>
    <p class="reward_info tcolor">
        <font color="#F76C6A">{{ $rewards_info['count'] }} </font>次打赏，共
        <font color="#F76C6A">{{ $rewards_info['amount'] / 100 }} </font>元
    </p>

    {{-- 打賞 --}}
    @if (!$rewards_data->isEmpty())
    <div class="reward_user">
        @foreach ($rewards_data as $reward)
        <div class="user_item">
            <img class="lazy round" data-original="{{ $reward['user']['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50" width="42" />
            @if ($reward['user']['verified'])
                <img class="verified_icon" src="{{ asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
            @endif
        </div>
        @endforeach
        <span class="more_user" onclick="rewarded.list({{ $rewards_id }}, '{{ $rewards_type }}')"></span>
    </div>
    @endif
</div>