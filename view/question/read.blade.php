@section('title') 问答详情 @endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/q_d.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/feed.css') }}" />
@endsection

@section('content')
<div class="questionpage">
    <!-- question-header -->
    <div class="questionheader">
        <div class="questionheader-content">
            <div class="questionheader-main">
                @if($question->amount > 0)
                    <span class="questionheader-price">￥{{ sprintf("%.2f", $question->amount/100) }}</span>
                @endif
                <div class="questionheader-tags">
                    <div class="questionheader-topics">
                        @if (!$question->topics->isEmpty())
                            @foreach ($question->topics as $topic)
                                <div class="tag questiontopic">
                                    <span class="tag-content">
                                        <a class="topiclink" href="#">{{ $topic->name }}</a>
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <h1 class="questionheader-title">{{ $question->subject }}</h1>
                <div class="questionheader-detail">
                    <!-- js增删  .questionrichtext--collapsed 改变content字数 -->
                    <div class="questionrichtext questionrichtext--expandable questionrichtext--collapsed">
                        <div>

                            <span class="show-body">{!! $question->body_html = Parsedown::instance()->setMarkupEscaped(true)->text($question->body) !!}</span>
                            {{--<span class="richtext" itemprop="text">{!! str_limit(preg_replace('/\@\!\[\]\([0-9]+\)/', '', $question->body), 300, '...') !!}</span>--}}
                            {{--<button class="button button-plain button-more questionrichtext-more">显示全部</button>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="questionheader-side">
                <div class="questionheader-follow-status">
                    <div class="questionfollowstatus">
                        <div class="numberboard questionfollowstatus-counts">
                            <button class="button numberboard-item button-plain" type="button">
                                <div class="numberboard-value">{{ $question->watchers_count }}</div>
                                <div class="numberboard-name">关注</div>
                            </button>
                            <div class="numberboard-divider"></div>
                            <div class="numberboard-item">
                                <div class="numberboard-value">{{ $question->views_count }}</div>
                                <div class="numberboard-name">浏览</div>
                            </div>
                        </div>
                        @if (!$question->invitations->isempty())
                            @foreach($question->invitations  as $invitation)
                                <button class="button questionfollowstatus-people button-plain" type="button">
                                     <span class="questionfollowstatus-people-tip">已邀请悬赏:
                                         <span class="userlink">
                                             <img class="avatar avatar--round" width="30px" height="30px" src="{{$invitation['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" alt="">
                                         </span>
                                         {{$invitation['name']}}
                                     </span>
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="questionheader-footer">
            <div class="questionheader-footer-inner">
                <div class="questionheader-main questionheader-footer-main">
                    @if($question['look'] == 1 && isset($TS) && $question['user_id'] == $TS['id'])
                        <span class="questionheader-onlook">￥0.1围观</span>
                    @endif
                    <div class="questionheaderactions">
                        <div class="questionheader-comment">
                            <button class="button button-plain" type="button" id="comment-button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                {{ $question->comments_count }} 评论
                            </button>
                        </div>
                        <div class="popover sharemenu">
                            <button class="button button-plain show-share" type="button" onclick="question.share({{$question['id']}})">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                                分享
                            </button>
                            <div class="share-show">
                                分享至：
                                @php
                                    // 设置第三方分享图片
                                    preg_match('/<img src="(.*?)".*?/', $question->body_html, $imgs);
                                    if (count($imgs) > 0) {
                                        $share_pic = $imgs[1];
                                    } else {
                                        $share_pic = '';
                                    }

                                @endphp
                                @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:questionread', ['question_id' => $question->id]), 'share_title' => addslashes(preg_replace('/\@\!\[\]\([0-9]+\)/', '', $question->body)), 'share_pic' => $share_pic])
                                <div class="triangle"></div>
                            </div>
                        </div>
                        @if($question['user_id'] == $TS['id'])
                            <a class="button button-plain" type="button" href="{{ route('pc:createquestion', $question['id']) }}">
                               <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bianji2"></use></svg>
                                编辑
                            </a>
                            @if($question->amount <= 0)
                                <a href="javascript:;" class="button set-amount" onclick="question.amount({{ $question['id'] }})">未设置悬赏</a>
                            @elseif($question->invitations->isempty())
                                <a href="javascript:;" class="button set-amount">已邀请悬赏</a>
                            @else
                                <a href="javascript:;" class="button set-amount">已设置悬赏</a>
                            @endif

                            <button class="button button-plain options" onclick="options(this)" type="button" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon icon-gengduo-copy" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                            </button>
                            <div class="options_div">
                                <div class="triangle"></div>
                                <ul>
                                    <li>
                                        <a href="javascript:;" onclick="question.selected({{ $question['id'] }}, 1)">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jingxuanwenda"></use></svg>申请为精选问答
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" onclick="question.delQuestion('{{$question['id']}}')">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shanchu-copy1"></use></svg>删除
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        @else
                            <button class="button button-plain" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy1"></use></svg>
                                收藏
                            </button>
                            {{--<button class="button button-plain" type="button">--}}
                                {{--<svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>--}}
                                {{--举报--}}
                            {{--</button>--}}
                        @endif

                    </div>
                    <div class="questionheader-actions"></div>
                </div>
                <div class="questionheader-side">
                    <div class="question-button-group">
                        @if (isset($TS) && $question->watched)
                            <button class="button button-grey watched" type="button" data-watched="1">已关注</button>
                        @else
                            <button class="button button-primary button-blue watched" type="button" data-watched="0">关注</button>
                        @endif
                            @if($question->my_answer == null)
                                <button class="button button-blue button-primary" id="write-answer" type="button">写回答</button>
                            @else
                                <a class="button button-blue button-primary"  href="{{ route('pc:answeread', $question->my_answer['id']) }}" type="button">查看回答</a>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /question-header -->
    <!-- quesition-main -->
    <div class="question-main">
        <div class="detail_comment question_coment hide">
            <span id="answer-button" class="answer-button"><img src="{{asset('zhiyicx/plus-component-pc/images/arrow_news_up.png')}}" alt=""></span>
            <div class="comment_title"><span class="comment_count cs{{$question->id}}">{{$question['comments_count']}}</span>人评论</div>
            <div class="comment_box">
                    <textarea
                            class="comment_editor"
                            id="J-editor{{$question->id}}"
                            placeholder="说点什么吧"
                            onkeyup="checkNums(this, 255, 'nums');"
                    ></textarea>
                <div class="comment_tool">
                    <span class="text_stats">可输入<span class="nums mcolor"> 255 </span>字</span>
                    <button
                            class="btn btn-primary"
                            id="J-button{{$question->id}}"
                            onclick="question.addComment({{$question->id}}, 0)"
                    > 评 论 </button>
                </div>
            </div>
            <div class="comment_list J-commentbox" id="J-commentbox{{$question->id}}">
            </div>
        </div>
        <div class="question-main-l">
            <div class="question-answers">
                <div class="question-answers-list">
                    <div class="question-answers-list-header">
                        <h4 class="headertxt"><span class="qs{{$question->id}}">{{$question['answers_count']}}</span>个回答</h4>
                        <div data-value="" class="zy_select t_c gap12 ">
                            <span>默认排序</span>
                            <ul>
                                <li data-type="default" class="active">默认排序</li>
                                <li data-type="time">时间排序</li>
                            </ul>
                            <i></i>
                        </div>
                    </div>
                    <div id="question-answers-list">
                    </div>
                </div>
            </div>
        </div>
        <!-- 发布回答 start-->
        <div class="question-main-r hide">
            <div class="user-mine">
            <span class="user-link">
                <img class="avatar avatar--round" width="55" height="55" src="{{$TS['avatar']  or asset('zhiyicx/plus-component-pc/images/avatar.png')}}" alt="">
            </span>
                <div class="author-info-content">
                    <div class="author-info-head">
                        <span class="author-info-name">{{ $TS['name'] }}</span>
                    </div>
                    <div class="author-info-tag">
                        @if(isset($TS->tags) && !$TS->tags->isEmpty())
                            @foreach($TS->tags as $tag)
                                <span>{{$tag['name']}}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="answer-send">
                @include('pcview::widgets.markdown', ['height'=>'400px', 'width' => '99%', 'content'=>''])
            </div>
            <div class="answer-anonymity">
                <input id="anonymity" name="anonymity" type="checkbox" class="input-checkbox"/>
                <label for="anonymity">启动匿名</label>
            </div>
            <div class="answer-anonymity"><button id="answer-send">提交</button></div>
        </div>
        <!----发布回答 end-->
    </div>

    <!-- /quesition-main -->

    {{-- 相关问题推荐 --}}
    @include('pcview::widgets.relevantquestion')

</div>
@endsection
@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.question.js')}}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/module.bdshare.js') }}"></script>
    <script src="{{ asset('zhiyicx/plus-component-pc/js/qrcode.js') }}"></script>

    <script>
        setTimeout(function() {
            scroll.init({
                container: '#question-answers-list',
                loading: '.question-main-l',
                url: '/question/{{$question['id']}}/answers',
                paramtype: 1,
                params: {order_type: 'default', limit: 2}
            });
        }, 300);
        // 展示问题详情
        $('.questionrichtext-more').on('click', function () {
            var _this = $(this);
            _this.siblings('.richtext').slideUp();
            _this.siblings('.hide').slideDown();
            _this.remove();
        });
        // 关注/取消关注问题
        $('.question-button-group').on('click', '.watched', function () {
            checkLogin();
            var _this = $(this);
            var watched = _this.data('watched');
            var type = '';
            if (watched == '1') {
                type = 'DELETE';
            } else {
                type = 'PUT';
            }
            $.ajax({
                url: '/api/v2/user/question-watches/{{$question['id']}}',
                type: type,
                data: {},
                success: function(res) {
                    if (watched == '1') {
                        _this.removeClass('button-grey').addClass('button-primary button-blue').text('关注');
                        _this.data('watched', '0');
                    } else {
                        _this.removeClass('button-primary button-blue').addClass('button-grey').text('已关注');
                        _this.data('watched', '1');
                    }
                },
                error: function(xhr){
                    showError(xhr.responseJSON);
                }
            })
        });
        // 切换问题排序
        $('.zy_select').on('click', 'li', function() {
            var type = $(this).data('type');
            // 清空数据
            $('#question-answers-list').html('');
            setTimeout(function() {
                scroll.init({
                    container: '#question-answers-list',
                    loading: '.question-main-l',
                    url: '/question/{{$question['id']}}/answers',
                    paramtype: 1,
                    params: {order_type: type, limit: 10}
                });
            }, 300);
        });

        $('#write-answer').on('click', function () {
            $('.question-main-r').slideDown();
            window.editor.resize();
            var _targetTop = $('.question-main-r').offset().top;//获取位置
            jQuery("html,body").animate({scrollTop:_targetTop},300);//跳转
        });
        $('.show-share').on('click', function () {
            var _this = $(this);
            _this.siblings('.share-show').stop().fadeToggle();
        });
        // 回答问题
        $('#answer-send').on('click', function () {
            var args = {
                'body': editor.getMarkdown(),
                'anonymity': $("input[type='checkbox'][name='anonymity']:checked").val() == 'on' ? 1 : 0
            };
            if (args.body == '') {
                noticebox('回答内容不能为空', 0);

                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/api/v2/questions/{{$question['id']}}/answers',
                data: args,
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        noticebox(res.message, 1, '/question/{{ $question['id'] }}');
                    }
                },
                error:function (xml) {
                    showError(xml);
                }
            });
        });

        $('#comment-button').on('click', function(){
            $('.detail_comment').show();
            $('.question-main-l').hide();
            $('.question-main-r').hide();
            setTimeout(function() {
                scroll.init({
                    container: '.J-commentbox',
                    loading: '.detail_comment',
                    url: '/question/{{$question->id}}/comments' ,
                    canload: true
                });
            }, 300);
        });

        $('#answer-button').on('click', function () {
            $('.question-main-l').show();
            $('.detail_comment').hide();
            $('.question-main-r').hide();
            setTimeout(function() {
                scroll.init({
                    container: '#question-answers-list',
                    loading: '.question-main-l',
                    url: '/question/{{$question['id']}}/answers',
                    paramtype: 1,
                    params: {order_type: 'default', limit: 10}
                });
            }, 300);
        });
    </script>
@endsection