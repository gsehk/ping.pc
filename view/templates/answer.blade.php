
@foreach ($datas as $data)
	<div class="qa-item">
	    <div class="qa-body mb20 clearfix">
	        @if (0)
	        	<img class="fl mr20" src="{{ asset('zhiyicx/plus-component-pc/images/pic_locked.png') }}" height="100">
	        @endif
	        <span class="tcolor margin0">{!! str_limit(preg_replace('/\@*\!\[\w*\]\([0-9]+\)/', '', $data->body), 280, '...') !!}</span><a href="{{ route('pc:answeread', $data->id) }}" class="button button-plain button-more">查看详情</a>
	    </div>
	    <div class="qa-toolbar feed_datas font14">
			<a href="javascript:;" class="gcolor liked" id="J-likes{{$data->id}}" onclick="liked.init({{$data->id}}, 'question', 1);" status="{{(int) (isset($TS) && $data->liked)}}" rel="{{ $data['likes_count'] }}">
				@if(isset($TS) && $data->liked)
					<svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-red"></use></svg>
				@else
					<svg class="icon" aria-hidden="true"><use xlink:href="#icon-xihuan-white"></use></svg>
				@endif
				<font>{{ $data->likes_count }}</font> 点赞
			</a>
	        <a class="gcolor comment J-comment-show" href="javascript:;">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg> <font class="cs{{$data->id}}">{{$data->comments_count}}</font> 评论
	        </a>

	        <span class="options">
	            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
	        </span>
	    </div>
		<div class="comment_box" style="display: none;">
			<div class="comment_line comment_line_answer">
				<div class="tr2"></div>
			</div>
			<div class="comment_body" id="comment_box{{$data->id}}">
				<div class="comment_textarea">
					<textarea class="comment-editor" id="J-editor{{$data->id}}" onkeyup="checkNums(this, 255, 'nums');"></textarea>
					<div class="comment_post">
						<span class="dy_cs">可输入<span class="nums" style="color: rgb(89, 182, 215);">255</span>字</span>
						<a class="btn btn-primary fr" id="J-button{{$data->id}}" onclick="QA.addComment({{$data->id}}, 1)"> 评 论 </a>
					</div>
				</div>
				<div id="J-commentbox{{ $data->id }}">
					@if($data->comments->count())
						@foreach($data->comments as $cv)
							<p class="comment_con" id="comment{{$cv->id}}">
								<span class="tcolor">{{ $cv->user['name'] }}：</span>
								@if ($cv->reply_user != 0)
									@php
										$user = getUserInfo($cv->reply_user);
									@endphp
									回复{{ '@'.$user->name }}：
								@endif

								{{$cv->body}}
								@if($cv->user_id != $TS['id'])
									<a onclick="comment.reply('{{$cv['user']['id']}}', {{$cv['commentable_id']}}, '{{$cv['user']['name']}}')">回复</a>
								@else
									<a class="comment_del" onclick="comment.pinneds('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">申请置顶</a>
									<a class="comment_del" onclick="comment.delete('{{$cv['commentable_type']}}', {{$cv['commentable_id']}}, {{$cv['id']}})">删除</a>
								@endif
							</p>
						@endforeach
					@endif
				</div>
				@if($data->comments->count() >= 5)
					<div class="comit_all font12"><a href="{{Route('pc:answeread', $data->id)}}">查看全部评论</a></div>
				@endif

			</div>
		</div>
	</div>
@endforeach