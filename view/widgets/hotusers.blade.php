<div class="hot_users clearfix">
	<div class="hot_users_title">
		热门用户
	</div>
	<ul>
		@foreach($users as $user)
		<li>
			<div class="hot_user_avatar">
				<img src="{{ $user['avatar'] or $routes['resource'] . '/images/avatar.png' }}">
			</div>
			<div class="hot_user_info">
				<a href=""><span class="hot_user_name">{{ $user['name'] }}</span></a>
				<div class="hot_user_intro">{{ $user['bio'] or '这家伙很懒，什么都没留下'}}</div>
			</div>
		</li>
		@endforeach
	</ul>
</div>