
@if (!$data->isEmpty())
@foreach ($data as $post)
    <div class="q_c">
        <h2 class="q_title">
            <a href="{{ route('pc:questionread', ['question_id' => $post['id']]) }}">{{ $post->subject }}</a>
        </h2>
        @if ($post->answer)
            <div class="q-answer">
                <div class="q_user">
                @if ($post->anonymity)
                    <div class="q_user_info">匿名</div>
                @else
                    <img src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=24" >
                    <div class="q_user_info">
                        <span>{{ $post->user->name }}</span>
                        @foreach ($post->user->tags as $tag)
                             <div>{{$tag->name}}</div>
                         @endforeach
                    </div>
                    <span class="q_time">{{ $post->answer->created_at }}</span>
                @endif
                </div>
                <div class="q_detail clearfix">
                    {{-- <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" >
                        </div>
                    </div> --}}
                    <div class="q_text">
                        <span>{{ str_limit($post->answer->body, 250) }}</span>
                        <button class="button button-plain Button--more">查看详情</button>
                    </div>
                </div>
            </div>
        @endif
        <div class="q_action">
            <button class="button button-plain">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-guanzhu"></use></svg>
                {{ $post->watchers_count }} 关注
            </button>
            <button class="button button-plain">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-huida"></use></svg>
                {{ $post->answers_count }} 条回答
            </button>
            <button class="button button-plain">
               <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jinqian"></use></svg>
               {{ $post->amount/100 }}
            </button>
            {{-- <button class="button button-plain fr">
                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
            </button> --}}
        </div>
    </div>
@endforeach
@endif
