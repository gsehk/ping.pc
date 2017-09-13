
 @extends('pcview::layouts.default') @section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/profile.css') }}" /> @endsection @section('content')
<div class="question_left_container">
    <div class="question_nav">
        <a href="#">问答</a>
        <a class="active" href="#">话题</a>
    </div>
    <!-- 话题 -->
    <div class="question_body" style="display: none;">
        <div class="question_sub_nav">
            <a class="active" href="#">全部话题</a>
            <a href="#">我关注的</a>
        </div>
        <ul class="topic_list">
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
            <li>
                <div class="topic_l">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="话题封面">
                </div>
                <div class="topic_r">
                    <p>游戏</p>
                    <div>关注
                        <span>344</span>问题
                        <span>400</span>
                    </div>
                    <a href="#">+关注</a>
                </div>
            </li>
        </ul>
        <!-- pager -->
        <!-- /pager -->
    </div>
    <!-- /话题 -->

    <!-- 问答 -->
    <div class="question_body">
        <div class="question_sub_nav">
            <a class="active" href="#">最新</a>
            <a href="#">精选</a>
            <a href="#">悬赏</a>
            <a href="#">热门</a>
            <a href="#">全部</a>
        </div>
        <!-- ul>li 替换为 div>div 保持class不变 -->
        <ul class="question_list">
            <li class="q_c">
                <h2 class="q_title">哈利波特同人文出于什么心理？</h2>
                <div class="q_user">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="user_header">
                    <div class="q_user_info">
                        <span>jsonleex</span>
                        <div>ddsadasfdfa</div>
                        <div>fafafasfas</div>
                        <div>fafsaasda</div>
                    </div>
                    <span class="q_time">2分钟前</span>
                </div>
                <div class="q_detail clearfix">
                    <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" alt="">
                        </div>
                    </div>
                    <div class="q_text">
                        <span>新闻热点 国内国外，前端最新动态 ECMAScript 2017（ES8）正式发布：ECMAScript 2017 或 ES8 于 2017 年六月底由 TC39正式发布，可以在这里浏览完整的版本；ES…</span>
                        <button class="Button Button--plain btn_more">查看详情</button>
                    </div>
                </div>
                <div class="q_action">
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        ￥18.00
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        30+回答
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        200+关注
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 4" class="Icon" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <g>
                                    <circle cx="2" cy="2" r="2"></circle>
                                    <circle cx="9" cy="2" r="2"></circle>
                                    <circle cx="16" cy="2" r="2"></circle>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
            </li>
            <li class="q_c">
                <h2 class="q_title">哈利波特同人文出于什么心理？</h2>
                <div class="q_user">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="user_header">
                    <div class="q_user_info">
                        <span>jsonleex</span>
                        <div>ddsadasfdfa</div>
                        <div>fafafasfas</div>
                        <div>fafsaasda</div>
                    </div>
                    <span class="q_time">2分钟前</span>
                </div>
                <div class="q_detail clearfix">
                    <div class="q_text">
                        <span>新闻热点 国内国外，前端最新动态 ECMAScript 2017（ES8）正式发布：ECMAScript 2017 或 ES8 于 2017 年六月底由 TC39正式发布，可以在这里浏览完整的版本；ES…</span>
                        <button class="Button Button--plain btn_more">查看详情</button>
                    </div>
                </div>
                <div class="q_action">
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        ￥18.00
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        30+回答
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        200+关注
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 4" class="Icon" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <g>
                                    <circle cx="2" cy="2" r="2"></circle>
                                    <circle cx="9" cy="2" r="2"></circle>
                                    <circle cx="16" cy="2" r="2"></circle>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
            </li>
            <li class="q_c">
                <h2 class="q_title">哈利波特同人文出于什么心理？</h2>
                <div class="q_user">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="user_header">
                    <div class="q_user_info">
                        <span>jsonleex</span>
                        <div>ddsadasfdfa</div>
                        <div>fafafasfas</div>
                        <div>fafsaasda</div>
                    </div>
                    <span class="q_time">2分钟前</span>
                </div>
                <div class="q_detail clearfix">
                    <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" alt="">
                        </div>
                    </div>
                    <div class="q_text">
                        <span>新闻热点 国内国外，前端最新动态 ECMAScript 2017（ES8）正式发布：ECMAScript 2017 或 ES8 于 2017 年六月底由 TC39正式发布，可以在这里浏览完整的版本；ES…</span>
                        <button class="Button Button--plain btn_more">查看详情</button>
                    </div>
                </div>
                <div class="q_action">
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        ￥18.00
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        30+回答
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        200+关注
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 4" class="Icon" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <g>
                                    <circle cx="2" cy="2" r="2"></circle>
                                    <circle cx="9" cy="2" r="2"></circle>
                                    <circle cx="16" cy="2" r="2"></circle>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
            </li>
            <li class="q_c">
                <h2 class="q_title">哈利波特同人文出于什么心理？</h2>
                <div class="q_user">
                    <img src="http://blog.jsonleex.com/icon/LX.png" alt="user_header">
                    <div class="q_user_info">
                        <span>jsonleex</span>
                        <div>ddsadasfdfa</div>
                        <div>fafafasfas</div>
                        <div>fafsaasda</div>
                    </div>
                    <span class="q_time">2分钟前</span>
                </div>
                <div class="q_detail clearfix">
                    <div class="q_img">
                        <div class="img_wrap">
                            <img src="http://blog.jsonleex.com/icon/LX.png" alt="">
                        </div>
                    </div>
                    <div class="q_text">
                        <span>新闻热点 国内国外，前端最新动态 ECMAScript 2017（ES8）正式发布：ECMAScript 2017 或 ES8 于 2017 年六月底由 TC39正式发布，可以在这里浏览完整的版本；ES…</span>
                        <button class="Button Button--plain btn_more">查看详情</button>
                    </div>
                </div>
                <div class="q_action">
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        ￥18.00
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        30+回答
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 18" class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <path d="M7.24 16.313c-.272-.047-.553.026-.77.2-1.106.813-2.406 1.324-3.77 1.482-.16.017-.313-.06-.394-.197-.082-.136-.077-.308.012-.44.528-.656.906-1.42 1.11-2.237.04-.222-.046-.45-.226-.588C1.212 13.052.027 10.73 0 8.25 0 3.7 4.03 0 9 0s9 3.7 9 8.25-4.373 9.108-10.76 8.063z"></path>
                            </g>
                        </svg>
                        200+关注
                    </button>
                    <button class="Button Button--plain">
                        <svg viewBox="0 0 18 4" class="Icon" width="20" height="20" aria-hidden="true">
                            <title></title>
                            <g>
                                <g>
                                    <circle cx="2" cy="2" r="2"></circle>
                                    <circle cx="9" cy="2" r="2"></circle>
                                    <circle cx="16" cy="2" r="2"></circle>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>
            </li>
        </ul>
    </div>
    <!-- /问答 -->
</div>
<div class="right_container">
    @include('pcview::widgets.hotissues') {{-- @include('pcview::widgets.answerank') --}}
</div>
@endsection