@extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="fan_cont">
    @if($type == 3)
        <div class="visitor_div">近期有<span>30</span>位Tser看过我</div>
    @else
    <ul class="fan_ul">
        <li><a href="{{Route('pc:myFans', ['type'=>1])}}" @if($type == 1) class="a_border" @endif>粉丝</a></li>
        <li><a href="{{Route('pc:myFans', ['type'=>2])}}" @if($type == 2) class="a_border" @endif>关注</a></li>
    </ul>
    @endif
    <div>
        <div class="fan_list">
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care c_ccc">已关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
        </div>
        <div class="fan_list">
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care c_ccc">已关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
        </div>
        <div class="fan_list">
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care c_ccc">已关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
            <div class="fan_c">
                <div class="fanList_top">
                    <div class="fan_header">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="head_img" />
                    </div>
                    <div class="fan_word">
                        <div>
                            <span class="fan_name">大狐狸</span>
                            <span class="fan_care">+关注</span>
                        </div>
                        <div class="fan_subtitle">大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头大姐头</div>
                        <div class="fan_number">
                            <span class="fan_num">粉丝<span>1212</span></span>
                            <span class="fan_careNum">关注<span>1212</span></span>
                        </div>
                    </div>
                </div>
                <div class="fan_line"></div>
                <div class="fanList_bottom">
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                    <a href="javascript:;"> <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/picture.png') }}" /></a>
                </div>
            </div>
        </div>
    </div>
</div>

@component('pages')

@endcomponent

@endsection
