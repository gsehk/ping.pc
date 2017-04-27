@extends('layouts.default')

@section('body_class')class="gray"@endsection

@section('content')
<div class="dy_bg fans_bg">
    <div class="dy_cont list_bg">
      <ul class="list_ul">
          <li><a href="javascript:;" class="fs-16 a_border">用户排行榜</a></li>
          <li><a href="javascript:;" class="fs-16">好友排行榜</a></li>
          <li><a href="javascript:;" class="fs-16">分享排行榜</a></li>
      </ul>
        <div class="fans_div">
            <div class="list_fans fans1">
                <div class="fans_list">
                    <span style =" display: inline-block; ">粉丝排行榜</span>
                    <div class="fList_r">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_lastpage_light.png') }}" />
                        <span >1/10</span>
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_nextpage.png') }}" />
                    </div>
                </div>
                <div class="list_pm fs-14">
                    <span class="pm_1">排名</span>
                    <span class="pm_2">昵称</span>
                    <span class="pm_3">粉丝数</span>
                </div>
                <ul class="fans_ul">
                    <li>
                        <div class="fans_span1"><span>1</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>2</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span >3</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>4</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>5</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span >6</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>7</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>8</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">9</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">10</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                </ul>
                <div class="fans_ranking">您在全站粉丝排行榜中排第<span>3000</span>名</div>
            </div>
            <div class="list_fans ">
                <div class="fans_list">
                    <span style=" display: inline-block; ">积分排行榜</span>
                    <div class="fList_r">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_lastpage_light.png') }}" />
                        <span>1/10</span>
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_nextpage.png') }}" />
                    </div>
                </div>
                <div class="list_pm fs-14">
                    <span class="pm_1">排名</span>
                    <span class="pm_2">昵称</span>
                    <span class="pm_3">粉丝数</span>
                </div>
                <ul class="fans_ul">
                    <li>
                        <div class="fans_span1"><span>1</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>2</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>3</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>4</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>5</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>6</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>7</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>8</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">9</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">10</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                </ul>
                <div class="fans_ranking">您在全站粉丝排行榜中排第<span>3000</span>名</div>
            </div>
        </div>
        <div class="fans_div">
            <div class="list_fans fans1">
                <div class="fans_list">
                    <span style=" display: inline-block; ">内容发布排行榜</span>
                    <div class="fList_r">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_lastpage_light.png') }}" />
                        <span>1/10</span>
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_nextpage.png') }}" />
                    </div>
                </div>
                <div class="list_pm fs-14">
                    <span class="pm_1">排名</span>
                    <span class="pm_2">昵称</span>
                    <span class="pm_3">粉丝数</span>
                </div>
                <ul class="fans_ul">
                    <li>
                        <div class="fans_span1"><span>1</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>2</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>3</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>4</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>5</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>6</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>7</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>8</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">9</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">10</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                </ul>
                <div class="fans_ranking">您在全站粉丝排行榜中排第<span>3000</span>名</div>
            </div>
            <div class="list_fans ">
                <div class="fans_list">
                    <span style=" display: inline-block; ">累计签到排行榜</span>
                    <div class="fList_r">
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_lastpage_light.png') }}" />
                        <span>1/10</span>
                        <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/ic_nextpage.png') }}" />
                    </div>
                </div>
                <div class="list_pm fs-14">
                    <span class="pm_1">排名</span>
                    <span class="pm_2">昵称</span>
                    <span class="pm_3">粉丝数</span>
                </div>
                <ul class="fans_ul">
                    <li>
                        <div class="fans_span1"><span>1</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>2</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>3</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>4</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>5</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>6</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>7</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span>8</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">9</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                    <li>
                        <div class="fans_span1"><span class="bg_ccc">10</span></div>
                        <div class="fans_span2">
                            <img src="{{ \Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset('images/cicle.png') }}" class="fans_img" />
                            12122
                        </div>
                        <div class="fans_span3">121</div>
                    </li>
                </ul>
                <div class="fans_ranking">您在全站粉丝排行榜中排第<span>3000</span>名</div>
            </div>
        </div>
    </div>
</div>
@endsection
