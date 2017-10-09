@if (!$data->isEmpty())
	@foreach ($data as $post)
		<div class="topic-item">
		    <div class="topic_l">
				<a href="{{ route('pc:topicinfo', $post->id) }}">
					<img src="{{ $post->avatar or asset('zhiyicx/plus-component-pc/images/default_picture.png') }}" alt="话题封面" width="140">
				</a>
		    </div>
		    <div class="topic_r">
				<a href="{{ route('pc:topicinfo', $post->id) }}">
					<p>{{ $post->name }}</p>
				</a>
		        <div>关注 <span>{{ $post->follows_count }}</span> 问题 <span>{{ $post->questions_count }}</span>
		        </div>
		        <div class="follow">
                    @if ($post->has_follow)
                        <a class="J-follow followed" tid="{{ $post->id }}"  status="1">已关注</a>
                    @else
                        <a class="J-follow" tid="{{ $post->id }}"  status="0">+关注</a>
                    @endif
                </div>
		    </div>
		</div>
	@endforeach
@endif