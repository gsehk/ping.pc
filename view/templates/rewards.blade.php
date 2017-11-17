@php
	use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
<div class="reward_popups" @if($rewards->count() < 6) style="height:auto;" @endif>
	<p class="reward_title ucolor font14">打赏列表</p>
	<div class="reward_popups_con">
		<ul class="reward_list" id="J-reward-list">
			@foreach($rewards as $reward)
				<li>
					<a href="{{ route('pc:mine', $reward->user->id) }}">
						<img src="{{ getAvatar($reward->user, 40) }}" class="lazy round" width="40"/>
					</a>
					<a href="{{ route('pc:mine', $reward->user->id) }}" class="uname">{{ $reward->user->name }}</a>
					<font color="#aaa">打赏了 {{ $app }}</font>
				</li>
			@endforeach
		</ul>
	</div>
</div>