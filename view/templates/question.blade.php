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
            <span class="q_time">{{ getTime($post->created_at) }}</span>
        </div>
        @if ($post->answer)
            <div class="q-answer">
                <div class="q_user">
                    @if ($post->answer->anonymity && !(isset($TS) && $post->answer->user_id == $TS['id']))
                        <img src="{{ asset('zhiyicx/plus-component-pc/images/ico_anonymity_60.png') }}?s=24" width="24px" height="24px" />
                        <div class="q_user_info">匿名用户</div>
                    @else
                        <div class="post-user-avatar">
                            <img src="{{ $post->answer->user->avatar or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=24" class="avatar">
                            @if ($post->answer->user->verified)
                                <img class="role-icon" src="{{ $post->answer->user->verified->icon or asset('zhiyicx/plus-component-pc/images/vip_icon.svg') }}">
                            @endif
                        </div>
                        <div class="q_user_info">
                            <span>{{ $post->answer->user->name }} {{ isset($TS) && $post->answer->anonymity == 1 && $post->answer->user_id == $TS['id'] ? '（匿名）' : ''}}</span>
                            @foreach ($post->answer->user->tags as $tag)
                                 <div>{{$tag->name}}</div>
                             @endforeach
                        </div>
                    @endif
                </div>
                <div class="q_detail clearfix">
                    @php
                        preg_match('/\@\!\[.*\]\((\d+)\)/i', $post->answer->body, $imgs);
                    @endphp
                    @if (count($imgs) > 0)
                        <div class="q_img">
                            <div class="img_wrap">
                                <img src="{{ $routes['storage'].$imgs[1] }}" height="100">
                            </div>
                        </div>
                    @endif
                    <div class="q_text">
                        @if(!$post->answer->invited || (isset($TS) && $post->answer->invited == 1 && ($post->answer->could || $post->user_id == $TS['id'] || $post->answer->user_id == $TS['id'])))
                            <span>{!! str_limit(preg_replace('@\@*\!\[\w*\]\(([https]+\:\/\/[\w\/\.]+|[0-9]+)\)@', '[图片]', $post->answer->body), 250, '...') !!}</span>
                            <a href="{{ route('pc:answeread', ['answer_id' => $post->answer->id]) }}" class="button button-plain button-more">查看详情</a>
                        @else
                            <span class="answer-body fuzzy" onclick="QA.look({{ $post->answer->id }}, '{{ sprintf("%.2f", $config['bootstrappers']['question:onlookers_amount']/100) }}' , {{ $post->id }}, this)">@php for ($i = 0; $i < 250; $i ++) {echo 'T';} @endphp</span>
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
            @if($post->amount >= 100)
                <button class="button button-plain">
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jinqian"></use></svg>
                    {{ $post->amount/100 }}
                </button>
            @endif
        </div>
    </div>
@endforeach
@endif
