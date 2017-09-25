<div class="hot_users clearfix">
	<div class="hot_users_title">
		热门用户
	</div>
	<ul>
		@foreach($users as $user)
		<li>
			<div class="hot_user_avatar">
				<a href="{{ route('pc:mine', $user['id']) }}"><img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=60"></a>
			</div>
			<div class="hot_user_info">
				<a href="{{ route('pc:mine', $user['id']) }}"><span class="hot_user_name">{{ $user['name'] }}</span></a>
				<div class="hot_user_intro">{{ $user['bio'] or '这家伙很懒，什么都没留下'}}</div>
			</div>
		</li>
		@endforeach
	</ul>
</div>