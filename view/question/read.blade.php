@section('title') 问答详情 @endsection @extends('pcview::layouts.default') @section('styles')
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/q_d.css') }}" /> @endsection @section('content')
<div class="QuestionPage">
    <!-- question-header -->
    <div class="QuestionHeader">
        <div class="QuestionHeader-content">
            <div class="QuestionHeader-main">
                <span class="QuestionHeader-price">￥123456</span>
                <div class="QuestionHeader-tags">
                    <div class="QuestionHeader-topics">
                        <div class="Tag QuestionTopic">
                            <span class="Tag-content">
                                <a class="TopicLink" href="#">建筑</a>
                            </span>
                        </div>
                        <div class="Tag QuestionTopic">
                            <span class="Tag-content">
                                <a class="TopicLink" href="#">建筑</a>
                            </span>
                        </div>
                        <div class="Tag QuestionTopic">
                            <span class="Tag-content">
                                <a class="TopicLink" href="#">建筑</a>
                            </span>
                        </div>
                        <div class="Tag QuestionTopic">
                            <span class="Tag-content">
                                <a class="TopicLink" href="#">建筑</a>
                            </span>
                        </div>
                    </div>
                </div>
                <h1 class="QuestionHeader-title">建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑</h1>
                <div class="QuestionHeader-detail">
                    <!-- js增删  .QuestionRichText--collapsed 改变content字数 -->
                    <div class="QuestionRichText QuestionRichText--expandable QuestionRichText--collapsed">
                        <div>
                            <span class="RichText" itemprop="text">建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑 建筑建筑建筑建筑建筑建筑建筑建筑建筑建
                                <a href="#">筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑 建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建
                                </a>筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑 建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑建筑…
                            </span>
                            <button class="Button Button--plain Button--more QuestionRichText-more">显示全部</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="QuestionHeader-side">
                <div class="QuestionHeader-follow-status">
                    <div class="QuestionFollowStatus">
                        <div class="NumberBoard QuestionFollowStatus-counts">
                            <button class="Button NumberBoard-item Button--plain" type="button">
                                <div class="NumberBoard-value">1257</div>
                                <div class="NumberBoard-name">关注</div>
                            </button>
                            <div class="NumberBoard-divider"></div>
                            <div class="NumberBoard-item">
                                <div class="NumberBoard-value">635713</div>
                                <div class="NumberBoard-name">浏览</div>
                            </div>
                        </div>
                        <button class="Button QuestionFollowStatus-people Button--plain" type="button">
                            <span class="QuestionFollowStatus-people-tip">
                                已邀请悬赏:
                                <span class="UserLink">
                                    <img class="Avatar Avatar--round" width="30px" height="30px" src="http://blog.jsonleex.com/icon/LX.png" alt="">
                                </span>
                                jsonleex
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="QuestionHeader-footer">
            <div class="QuestionHeader-footer-inner">
                <div class="QuestionHeader-main QuestionHeader-footer-main">
                    <span class="QuestionHeader-onlook">￥0.0围观</span>
                    <div class="QuestionHeaderActions">
                        <div class="QuestionHeader-Comment">
                            <button class="Button Button--plain" type="button">
                                <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                                2 评论
                            </button>
                        </div>
                        <div class="Popover ShareMenu">
                            <div>
                                <button class="Button Button--plain" type="button">
                                    <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                    </svg>
                                    分享
                                </button>
                            </div>
                        </div>
                        <button class="Button Button--plain" type="button">
                            <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            编辑
                        </button>
                        <button class="Button Button--plain" type="button">
                            <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            举报
                        </button>
                        <div class="Popover">
                            <button class="Button Button--plain" type="button" id="Popover-6485-72543-toggle" aria-haspopup="true" aria-expanded="false" aria-owns="Popover-6485-72543-content">
                                <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="QuestionHeader-actions"></div>
                </div>
                <div class="QuestionHeader-side">
                    <div class="QuestionButtonGroup">
                        <button class="Button Button--primary Button--blue" type="button">关注</button>
                        <!-- <button class="Button Button--grey" type="button">已关注</button> -->
                        <button class="Button Button--blue" type="button">写回答</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /question-header -->
    <!-- quesition-main -->
    <div class="Question-main">
        <div class="Question-main-l">
            <div class="Question-answers">
                <div class="Question-answers-list">
                    <div class="Question-answers-list-header">
                        <h4 class="HeaderTxt">30个回答</h4>
                        <div data-value="" class="zy_select t_c gap12 ">
                            <span>默认排序</span>
                            <ul>
                                <li data-value="user" class="active">默认排序</li>
                                <li data-value="org">时间排序</a>
                                </li>
                            </ul>
                            <i></i>
                        </div>
                    </div>
                    <div>
                        <div class="List-item">
                            <div class="List-item-header">
                                <span class="UserLink AuthorInfo-avatarWrapper">
                                    <img class="Avatar Avatar--round" width="44" height="44" src="http://blog.jsonleex.com/icon/LX.png" alt="">
                                </span>
                                <div class="AuthorInfo-content">
                                    <div class="AuthorInfo-head">
                                        <span class="UserLink AuthorInfo-name">jsonleex</span>
                                    </div>
                                    <div class="AuthorInfo-time">
                                        <span>5分钟前</span>
                                    </div>
                                </div>
                            </div>
                            <div class="List-item-content">
                                <div class="Content-inner">
                                    <span>5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前5分钟前...</span>
                                    <button class="Button Button--plain Button--more">查看详情</button>
                                </div>
        <div class="QuestionHeader-footer">
            <div class="QuestionHeader-footer-inner">
                <div class="QuestionHeader-main QuestionHeader-footer-main">
                    <span class="QuestionHeader-onlook">￥0.0围观</span>
                    <div class="QuestionHeaderActions">
                        <div class="QuestionHeader-Comment">
                            <button class="Button Button--plain" type="button">
                                <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                                2 评论
                            </button>
                        </div>
                        <div class="Popover ShareMenu">
                            <div>
                                <button class="Button Button--plain" type="button">
                                    <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                    </svg>
                                    分享
                                </button>
                            </div>
                        </div>
                        <button class="Button Button--plain" type="button">
                            <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            编辑
                        </button>
                        <button class="Button Button--plain" type="button">
                            <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                            </svg>
                            举报
                        </button>
                        <div class="Popover">
                            <button class="Button Button--plain" type="button" id="Popover-6485-72543-toggle" aria-haspopup="true" aria-expanded="false" aria-owns="Popover-6485-72543-content">
                                <svg class="Icon Icon--left" width="20" height="20" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-comment"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="QuestionHeader-actions"></div>
                </div>
                <div class="QuestionHeader-side">
                    <div class="QuestionButtonGroup">
                        <button class="Button Button--primary Button--blue" type="button">关注</button>
                        <!-- <button class="Button Button--grey" type="button">已关注</button> -->
                        <button class="Button Button--blue" type="button">写回答</button>
                    </div>
                </div>
            </div>
        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Question-main-r"></div>
    </div>
    <!-- /quesition-main -->
</div>
@endsection