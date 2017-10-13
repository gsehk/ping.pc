@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
@endphp
@if (!$data->isEmpty())
@foreach ($data as $post)
    <div class="q_c">
        <div class="q_c_t">
            <h2 class="q_title">
                <a href="{{ route('pc:questionread', ['question_id' => $post['id']]) }}">{{ $post->subject }}</a>
                @if($post['excellent'] == 1)
                    <span class="excellent">精</span>
                @endif
            </h2>
            <span class="q_time">{{ getTime($post->answer->created_at) }}</span>
        </div>
        @if ($post->answer)
            <div class="q-answer">
                <div class="q_user">
                    @if ($post->answer->anonymity)
                        <img src="{{ asset('zhiyicx/plus-component-pc/images/ico_anonymity_60.png') }}?s=24" >
                        <div class="q_user_info">匿名用户</div>
                    @else
                        <img src="{{ $post->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=24" >
                        <div class="q_user_info">
                            <span>{{ $post->user->name }}</span>
                            @foreach ($post->user->tags as $tag)
                                 <div>{{$tag->name}}</div>
                             @endforeach
                        </div>
                    @endif
                </div>
                <div class="q_detail clearfix">
                    {{-- <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" >
                        </div>
                    </div> --}}
                    <div class="q_text">

                        @if(!$post->answer->invited || (isset($TS) && $post->answer->invited == 1 && ($post->answer->could || $post->user_id == $TS['id'] || $post->answer->user_id == $TS['id'])))
                            <span>{!! str_limit(preg_replace('/\@\!\[\]\([0-9]+\)/', '', $post->answer->body), 250, '...') !!}</span>
                            <a href="{{ route('pc:answeread', ['answer_id' => $post->answer->id]) }}" class="button button-plain Button--more">查看详情</a>
                        @else
                            <span class="answer-body fuzzy">@php for ($i = 0; $i < 250; $i ++) {echo 'T';} @endphp</span>
                        @endif
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
        </div>
    </div>
@endforeach
@endif
