    @extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="w_815">
    <div class="int_top">
        <span class="intTop_left"></span>
        <div class="intTop_num">
            <span>{{$score or ''}}</span>
            <span class="int_this">当前积分</span>
        </div>
        <div class="int_sign"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/jifen_03.png') }}" class="sign_img" />每日签到</div>
    </div>
    <div class="int_cont">
        <ul class="list_ul int_rule">
            <li class="int_li"><a href="{{Route('pc:credit',['type'=>1])}}" class="fs-16 @if($type == 1) a_border @endif">积分记录</a></li>
            <li class="int_li2"><a href="{{Route('pc:credit',['type'=>2])}}" class="fs-16 @if($type == 2) a_border @endif">积分规则</a></li>
        </ul>
        @if($type == 1)
        <table class="score-table">
            <tbody>
                <tr>
                    <th width="40%">操作</th>
                    <th width="40%">积分</th>
                    <th width="20%">时间</th>
                </tr>
                @if(isset($record))
                    @foreach ($record as $post)
                    <tr>
                        <td>{{$post['action']}}</td>
                        <td class="c_f8">{!!$post['change']!!}</td>
                        <td>{{$post['created_at']}}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @else
        <table class="score-table">
            <tbody>
                <tr>
                    <th>操作</th>
                    <th>积分</th>
                </tr>
                @foreach($setting as $set)
                <tr>
                    <td align="left">{{$set['alias']}}</td>
                    @if($set['score'] > 0)
                    <td style="color: rgb(135, 206, 250);">+{{$set['score']}}</td>
                    @else
                    <td class="c_f8">{{$set['score']}}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
       
        @endif
    </div>
</div>
@endsection
