@if (!empty($rec_users))
<div class="dyrBottom">
    <ul>
        @foreach ($rec_users as $rec_user)
        <li>
            <a href="{{ route('pc:mine', ['user_id' => $rec_user['id']]) }}">
            <img src="{{ $rec_user['avatar'] }}" alt="{{ $rec_user['name'] }}"/>
            </a>
            <span><a href="{{ route('pc:mine', ['user_id' => $rec_user['id']]) }}">{{ $rec_user['name'] }}</a></span>
        </li>
        @endforeach
    </ul>
    <a class="dy_more fs-12" href="{{ route('pc:users', ['type'=>4]) }}">更多推荐用户</a>
</div>
@endif