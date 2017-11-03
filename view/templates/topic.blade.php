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
		        <div>关注 <span id="tf-count-{{ $post->id }}">{{ $post->follows_count }}</span> 问题 <span>{{ $post->questions_count }}</span>
		        </div>
		        <div class="follow">
                    @if ($post->has_follow)
                        <a class="followed" tid="{{ $post->id }}"  status="1" onclick="QT.follow(this)">已关注</a>
                    @else
                        <a tid="{{ $post->id }}"  status="0" onclick="QT.follow(this)">+关注</a>
                    @endif
                </div>
		    </div>
		</div>
	@endforeach
@elseif(isset($search) && $search)
	<div class="no_data_div">
		<div class="no_data">
			<img src="{{ asset('zhiyicx/plus-component-pc/images/pic_default_content.png') }}">
			<p> 没有找到相关话题~</p>
			<div class="search-button">
				<a href="javascript:;" onclick="QT.show()">向官方建议创建新话题</a>
			</div>
		</div>
	</div>
@endif