@extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="w_815">
    <div class="int_top">
        <span class="intTop_left"></span>
        <div class="intTop_num">
            <span>48754</span>
            <span class="int_this">当前积分</span>
        </div>
        <div class="int_sign"><img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/jifen_03.png') }}" class="sign_img" />每日签到</div>
    </div>
    <div class="int_cont">
        <ul class="list_ul int_rule">
            <li class="int_li"><a href="{{Route('pc:score', ['type'=>1])}}" class="fs-16 @if($type == 1) a_border @endif">积分记录</a></li>
            <li class="int_li2"><a href="{{Route('pc:score', ['type'=>2])}}" class="fs-16 @if($type == 2) a_border @endif">积分规则</a></li>
        </ul>
        @if($type == 1)
        <table class="score-table">
            <tbody>
                <tr>
                    <th width="40%">操作</th>
                    <th width="40%">积分</th>
                    <th width="20%">时间</th>
                </tr>
                <tr>
                    <td>121</td>
                    <td class="c_f8">+12</td>
                    <td>2017-4-17 21:27</td>
                </tr>
                <tr>
                    <td>121</td>
                    <td class="list_div2">+12</td>
                    <td>2017-4-17 21:27</td>
                </tr>
                <tr>
                    <td>121</td>
                    <td class="list_div2">+12</td>
                    <td>2017-4-17 21:27</td>
                </tr>
                <tr>
                    <td>121</td>
                    <td class="list_div2">+12</td>
                    <td>2017-4-17 21:27</td>
                </tr>
            </tbody>
        </table>
        @else
        <table class="score-table">
            <tbody>
                <tr>
                    <th>操作</th>
                    <th>积分</th>
                </tr>
                <tr>
                    <td align="left">用户登录</td>
                    <td class="c_f8">+12</td>
                </tr>
                <tr>
                    <td align="left">个人主页被访问</td>
                    <td class="list_div2">+12</td>
                </tr>
                <tr>
                    <td align="left">个人主页被访问</td>
                    <td class="list_div2">+12</td>
                </tr>
                <tr>
                    <td align="left">个人主页被访问</td>
                    <td class="list_div2">+12</td>
                </tr>
            </tbody>
        </table>
        @endif
        <!-- <div class="list_pm fs-14 int_pm">
            <span class="pm_1">操作</span>
            <span class="pm_2">积分</span>
            <span class="pm_3">时间</span>
        </div>
        <ul class="fans_ul operation_ul">
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2 c_f8">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
            <li>
                <div class="list_div1">121</div>
                <div class="list_div2">
                    +12
                </div>
                <div class="list_div3">2017-4-17 21:27</div>
            </li>
        </ul> -->
    </div>
    @component('pages')
    @endcomponent
</div>
@endsection
