
@foreach ($datas as $data)
	<div class="qa-item">
	    <h3 class="qa-title tcolor"><a href="{{ route('pc:questionread', $data->id) }}">{{ $data->subject }}</a></h3>
	    <div class="qa-toolbar font14">
	    @if ($data->anonymity)
	    	<a href="{{ route('pc:mine', $data->user->id) }}">
            	<img class="round mr10" src="{{ asset('zhiyicx/plus-component-pc/images/ico_anonymity_60.png') }}" width="30">
            </a>
	    	<span class="tcolor mr10">匿名</span>
	    @else
			<a href="{{ route('pc:mine', $data->user->id) }}">
            	<img class="round mr10" src="{{ $data->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}" width="30">
            </a>
	        <span class="tcolor mr10">{{ $data->user->name }}</span>·&nbsp;&nbsp;
	        @if ($data->user->tags)
		        <span class="gcolor">
		        	@foreach ($data->user->tags as $k=>$tag)
		        		{{ $tag->name }}@if (!$loop->last) /@endif
		        	@endforeach
		        </span>
	        @endif
	        <span class="gcolor ctime fr">{{ $data->created_at }}</span>
	    @endif
	    </div>
	    <div class="qa-body mt20 mb20 clearfix">
			@php
				preg_match('/\@\!\[.*\]\((\d+)\)/i', $data->body, $imgs);
			@endphp
	        @if (count($imgs) > 0)
	        	<img class="fl mr20" src="{{ $routes['storage'].$imgs[1] }}" height="100">
	        @endif
	        <p class="tcolor margin0"><a href="{{ route('pc:answeread', $data->id) }}">{{ $data->body }}</p></a>
	    </div>
	    <div class="qa-toolbar feed_datas font14">
			<a class="follow gcolor J-watched" data-id="{{ $data->id }}" status="{{(int) (isset($TS) && $data->watched)}}">
				<svg class="icon" aria-hidden="true"><use xlink:href="#icon-guanzhu"></use></svg>
				<span class="watched">{{ (isset($TS) && $data->watched) ? '已关注' : '关注' }}</span>
			</a>
			{{--<a class="follow gcolor" onclick="question.watched.init({{$data->id}});" id="J-watched{{$data->id}}" status="{{(int) (isset($TS) && $data->watched)}}">--}}
				{{--<svg class="icon" aria-hidden="true"><use xlink:href="#icon-guanzhu"></use></svg>--}}
				{{--<span>{{ (isset($TS) && $data->watched) ? '已关注' : '关注' }}</span>--}}
			{{--</a>--}}
	        <span class="answer gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> {{ $data->answers_count }}条 回答
	        </span>
	        <span class="mony gcolor">
	            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg> {{ $data->amount * ($config['bootstrappers']['wallet:ratio']/100/100) }}
	        </span>
	        <span class="options">
	            <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
	        </span>
	    </div>
	</div>
@endforeach