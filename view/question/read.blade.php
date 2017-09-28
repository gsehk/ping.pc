@section('title') 问答详情 @endsection

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/q_d.css') }}" />
@endsection

@section('content')
<div class="questionpage">
    <!-- question-header -->
    <div class="questionheader">
        <div class="questionheader-content">
            <div class="questionheader-main">
                <span class="questionheader-price">￥{{ $question->amount/100 }}</span>
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
                            <span class="hide">{!! $body = Parsedown::instance()->setMarkupEscaped(true)->text($question->body) !!}</span>
                            <span class="richtext" itemprop="text">{!! str_limit(strip_tags($body), 300, "...") !!}</span>
                            <button class="button button-plain button-more questionrichtext-more">显示全部</button>
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
                    <span class="questionheader-onlook">￥0.0围观</span>
                    <div class="questionheaderactions">
                        <div class="questionheader-comment">
                            <button class="button button-plain" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                {{ $question->comments_count }} 评论
                            </button>
                        </div>
                        <div class="popover sharemenu">
                            <div>
                                <button class="button button-plain" type="button">
                                   <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                                    分享
                                </button>
                            </div>
                        </div>
                        @if($question['user_id'] == $TS['id'])
                            <button class="button button-plain" type="button">
                               <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bianji2"></use></svg>
                                编辑
                            </button>
                        @else
                            <button class="button button-plain" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-shoucang-copy"></use></svg>
                                收藏
                            </button>
                            <button class="button button-plain" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-report"></use></svg>
                                举报
                            </button>
                        @endif
                        <div class="popover">
                            <button class="button button-plain" type="button" id="popover-6485-72543-toggle" aria-haspopup="true" aria-expanded="false" aria-owns="popover-6485-72543-content">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-gengduo-copy"></use></svg>
                             </button>
                         </div>
                    </div>
                    <div class="questionheader-actions"></div>
                </div>
                <div class="questionheader-side">
                    <div class="question-button-group">
                        @if ($question->watched)
                            <button class="button button-grey watched" type="button" data-watched="1">已关注</button>
                        @else
                            <button class="button button-primary button-blue watched" type="button" data-watched="0">关注</button>
                        @endif
                        <button class="button button-blue" id="write-answer" type="button">写回答</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /question-header -->
    <!-- quesition-main -->
    <div class="question-main">
        <div class="question-main-l">
            <div class="question-answers">
                <div class="question-answers-list">
                    <div class="question-answers-list-header">
                        <h4 class="headertxt">{{$question['answers_count']}}个回答</h4>
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
                @include('pcview::widgets.markdown', ['height'=>'400px', 'width' => '99%', 'content'=>'1'])
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
    <script>
        setTimeout(function() {
            scroll.init({
                container: '#question-answers-list',
                loading: '.question-answers-list',
                url: '/question/{{$question['id']}}/answers',
                paramtype: 1,
                params: {order_type: 'default', limit: 10}
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
                    loading: '.question-answers-list',
                    url: '/question/{{$question['id']}}/answers',
                    paramtype: 1,
                    params: {order_type: type, limit: 10}
                });
            }, 300);
        });

        $('#write-answer').on('click', function () {
            $('.question-main-r').slideDown();
            var _targetTop = $('.question-main-r').offset().top;//获取位置
            jQuery("html,body").animate({scrollTop:_targetTop},300);//跳转
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
                        noticebox(res.message, 1, '/question/{{$question['id']}}');
                    }
                },
                error:function (xml) {
                    console.log(xml);
                    showError(xml.responseJSON);
                }
            });
        });
    </script>
@endsection