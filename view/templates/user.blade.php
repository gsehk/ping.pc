@foreach ($users as $data)
	@if(($loop->iteration) % 3 == 1)
		<div class="user_list">
	@endif
	    <div class="user">
            <div class="user_header">
                <!-- <a href="{{route('pc:mine',['user_id'=>$data['id']])}}"> -->
                <a href="javascript:;">
                    <img src="{{ $data['avatar'] or $routes['resource'] . '/images/avatar.png' }} " class="user_avatar" alt="{{ $data['name'] }}"/>
                </a>
            </div>
            <div class="user_body">
                    <!-- <a href="{{route('pc:mine',['user_id'=>$data['id']])}}"> -->
                    <a href="javascript:;">
                        <span class="user_name">{{ $data['name'] or $data['phone'] }}</span>
                    </a>
                    @if ($data['follower'])
                    <span id="data" class="follow_btn followed" uid="{{ $data['id'] }}" status="1">已关注</span>
                    @else
                    <span id="data" class="follow_btn" uid="{{ $data['id'] }}" status="0">+关注</span>
                    @endif
                <div class="user_subtitle">{{ $data['bio'] or '这家伙很懒，什么都没留下'}}</div>
                <div class="user_number">
                    <span class="user_num">粉丝<span>{{ $data['extra']['followers_count'] or 0}}</span></span>
                    <span class="user_num right">关注<span>{{ $data['extra']['followings_count'] or 0}}</span></span>
                </div>
            </div>
	    </div>

	@if(($loop->iteration) % 3 == 0)

		</div>
	@elseif($loop->iteration == count($users))

		</div>
	@endif
@endforeach