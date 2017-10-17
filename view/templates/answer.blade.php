
@foreach ($datas as $data)
	<div class="qa-item">
	    <div class="qa-body mb20 clearfix">
	        @if (0)
	        	<img class="fl mr20" src="{{ asset('zhiyicx/plus-component-pc/images/pic_locked.png') }}" height="100">
	        @endif
	        <span class="tcolor margin0">{!! str_limit(preg_replace('/\@*\!\[\w*\]\([0-9]+\)/', '', $data->body), 280, '...') !!}</span><a href="{{ route('pc:answeread', $data->id) }}" class="button button-plain button-more">查看详情</a>
	    </div>
	    <div class="qa-toolbar feed_datas font14">
	        <a class="follow gcolor" href="{{ route("pc:answeread", $data->id) }}">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg> {{ $data->comments_count }}评论
	        </a>
	        <a class="answer gcolor" href="{{ route("pc:answeread", $data->id) }}">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg> {{ $data->likes_count }} 分享
	        </a>
			@if(isset($TS) && $data->user_id == $TS['id'] && $data->adoption != 1 && $data->invited != 1)
				<a class="mony gcolor" href="{{ route('pc:answeredit', $data->id) }}">
					<svg class="icon" aria-hidden="true"><use xlink:href="#icon-bianji2"></use></svg> 编辑
				</a>
			@endif
	        <span class="options">
	            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
	        </span>
	    </div>
	</div>
@endforeach