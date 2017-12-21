@foreach ($group as $item)
    <div class="group_item @if(($loop->iteration) % 2 == 0) group_item_right @endif">
        <dl class="clearfix">
            <dt><a href="{{Route('pc:groupread', $item->id)}}"><img src="@if(isset($item->avatar->id))  {{ $routes['storage'].$item->avatar->id }} @else {{ asset('zhiyicx/plus-component-pc/images/default_picture.png') }} @endif" width="120" height="120"></a></dt>
            <dd>
                <a class="title" href="{{Route('pc:groupread', $item->id)}}" alt="{{ $item->name }}">{{ str_limit($item->name, 20, '...') }}</a>
                <div class="tool">
                    <span>分享 <font class="mcolor">{{ $item->posts_count }}</font></span>
                    <span>订阅 <font class="mcolor" id="join-count-{{ $item->id }}">{{ $item->users_count }}</font></span>
                </div>
                <div class="join">
                    @if ($item->joined)
                        <button
                            class="J-join joined"
                            id="{{$item->id}}"
                            state="1"
                            mode="{{$item->mode}}"
                            money="{{$item->money}}"
                            onclick="grouped.init(this);"
                        >已加入</button>
                    @else
                        <button
                            class="J-join"
                            id="{{$item->id}}"
                            state="0"
                            mode="{{$item->mode}}"
                            money="{{$item->money}}"
                            onclick="grouped.init(this);"
                        >+加入</button>
                    @endif
                </div>
            </dd>
        </dl>
    </div>
@endforeach