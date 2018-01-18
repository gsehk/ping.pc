@section('title') 问答详情 @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/question.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/feed.css') }}" />
@endsection

@section('content')
<div class="questionpage">
    {{-- question-header --}}
    <div class="questionheader">
        <div class="questionheader-content">
            <div class="questionheader-main">
                @if($question->amount > 0)
                    <span class="questionheader-price">{{ sprintf("%.2f", $question->amount*($config['bootstrappers']['wallet:ratio']/100/100)) }}</span>
                @endif
                <div class="questionheader-tags">
                    <div class="questionheader-topics">
                        @if (!$question->topics->isEmpty())
                            @foreach ($question->topics as $topic)
                                <div class="tag questiontopic">
                                    <span class="tag-content">
                                        <a class="topiclink" href="{{ route('pc:topicinfo', $topic->id) }}">{{ $topic->name }}</a>
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <h1 class="questionheader-title">{{ $question->subject }}</h1>
                <div class="questionheader-detail">
                    {{-- js增删  .questionrichtext--collapsed 改变content字数 --}}
                    <div class="questionrichtext questionrichtext--expandable questionrichtext--collapsed">
                        <div>
                            @php
                                $body_text = preg_replace('@\@*\!\[\w*\]\([https]+\:\/\/[\w\/\.]+\)@', '[图片]', $question->body);
                            @endphp
                            @if(strpos($body_text, '[图片]') === false && strlen($body_text) <= 300)
                                <span class="show-body">{!! $question->body_html = Parsedown::instance()->setMarkupEscaped(true)->text($question->body) !!}</span>
                            @else
                                <span class="show-body hide">{!! $question->body_html = Parsedown::instance()->setMarkupEscaped(true)->text($question->body) !!}</span>
                                <span class="richtext" itemprop="text">{{ str_limit($body_text, 300, '...') }}</span>
                                <button class="button button-plain button-more questionrichtext-more" data-show="0">显示全部</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="questionheader-side">
                <div class="questionheader-follow-status">
                    <div class="questionfollowstatus">
                        <div class="numberboard questionfollowstatus-counts">
                            <button class="button numberboard-item button-plain" type="button">
                                <div class="numberboard-value" id="watchers_count_value">{{ $question->watchers_count }}</div>
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
                                         <a href="{{ route('pc:mine', $invitation['id']) }}">
                                             <span class="userlink">
                                                 <img class="avatar" width="30px" height="30px" src="{{ getAvatar($invitation, 30) }}" alt="">
                                                 @if ($invitation['verified'])
                                                     <img class="role-icon" src="{{ $invitation['verified']->icon or asset('assets/pc/images/vip_icon.svg') }}">
                                                 @endif
                                             </span>
                                             {{$invitation['name']}}
                                         </a>
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
                    @if($question['look'] == 1 && isset($question['invitation_answers']) && !$question['invitation_answers']->isEmpty() && $question['invitation_answers'][0]['onlookers_count'] > 0)
                        <span class="questionheader-onlook">{{ sprintf("%.2f", $question['invitation_answers'][0]['onlookers_count']*$config['bootstrappers']['question:onlookers_amount'] * ($config['bootstrappers']['wallet:ratio']/100/100)) }}围观</span>
                    @endif
                    <div class="questionheaderactions">
                        <div class="questionheader-comment">
                            <button class="button button-plain" type="button" id="comment-button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-comment"></use></svg>
                                {{ $question->comments_count }} 评论
                            </button>
                        </div>
                        <div class="popover sharemenu">
                            <button class="button button-plain show-share" type="button">
                                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                                分享
                            </button>
                            <div class="share-show">
                                分享至：
                                @php
                                    preg_match('/<img src="(.*?)".*?/', $question->body_html, $imgs);
                                    if (count($imgs) > 0) {
                                        $share_pic = $imgs[1];
                                    } else {
                                        $share_pic = '';
                                    }


                                @endphp
                                @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:questionread', ['question_id' => $question->id]), 'share_title' => addslashes(preg_replace('@\@*\!\[\w*\]\([https]+\:\/\/[\w\/\.]+\)@', '', $question->body)), 'share_pic' => $share_pic])
                                <div class="triangle"></div>
                            </div>
                        </div>
                        @if($question['user_id'] == $TS['id'])
                            <a class="button button-plain" type="button" href="{{ route('pc:createquestion', $question['id']) }}">
                               <svg class="icon" aria-hidden="true"><use xlink:href="#icon-bianji2"></use></svg>
                                编辑
                            </a>
                        @endif
                        @if($question->amount <= 0)
                            <a href="javascript:;" class="button set-amount" @if($question['user_id'] == $TS['id'] && $question['status'] == 0) onclick="question.amount({{ $question['id'] }})" @endif >未设置悬赏</a>
                        @elseif(!$question->invitations->isempty())
                            <a href="javascript:;" class="button set-amount">已邀请悬赏</a>
                        @else
                            <a href="javascript:;" class="button set-amount">已设置悬赏</a>
                        @endif
                        @if($question['user_id'] == $TS['id'])
                            <a class="button button-plain options" onclick="options(this)" type="button" aria-haspopup="true" aria-expanded="false">
                                <svg class="icon icon-more" aria-hidden="true"><use xlink:href="#icon-more"></use></svg>
                            </a>
                            <div class="options_div">
                                <div class="triangle"></div>
                                <ul>
                                    <li>
                                        <a href="javascript:;" onclick="question.selected({{ $question['id'] }}, {{ $config['bootstrappers']['question:apply_amount'] * ($config['bootstrappers']['wallet:ratio']/100/100) }})">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-jingxuanwenda"></use></svg>申请为精选问答
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" onclick="question.delQuestion('{{$question['id']}}')">
                                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-delete"></use></svg>删除
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
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
    {{-- /question-header --}}
    {{-- quesition-main --}}
    <div class="question-main">

        {{-- 评论 --}}
        @include('pcview::widgets.comments', ['id' => $question->id, 'comments_count' => $question->comments_count, 'comments_type' => 'question', 'loading' => '.detail_comment', 'add_class' => 'question_coment hide', 'position' => 0])

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
         {{-- 发布回答 start --}}
        <div class="question-main-r hide">
            <div class="user-mine">
            <span class="user-link">
                <img class="avatar avatar--round" width="55" height="55" src="{{ getAvatar($TS, 55) }}" alt="">
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
        {{-- --发布回答 end --}}
    </div>

    {{-- /quesition-main --}}

    {{-- 相关问题推荐 --}}
    @include('pcview::widgets.relevantquestion')

</div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/pc/js/md5.min.js')}}"></script>
    <script src="{{ asset('assets/pc/js/module.question.js')}}"></script>
    <script src="{{ asset('assets/pc/js/qrcode.js') }}"></script>

    <script>
        var checkSubmitFlg = false;
        scroll.init({
            container: '#question-answers-list',
            loading: '.question-main-l',
            url: '/question/{{$question['id']}}/answers',
            paramtype: 1,
            params: {order_type: 'default', limit: 10}
        });
        
        // 展示问题详情
        $('.questionrichtext-more').on('click', function () {
            var _this = $(this);
            var show = _this.data('show');
            if (show == '0') {
                _this.siblings('.richtext').hide();
                _this.siblings('.show-body').show();
                _this.text('收起');
                _this.data('show', '1');
            } else {
                _this.siblings('.show-body').hide();
                _this.siblings('.richtext').show();
                _this.text('显示全部');
                _this.data('show', '0');
            }
        });
        // 关注/取消关注问题
        $('.question-button-group').on('click', '.watched', function () {
            checkLogin();
            var _this = $(this);
            var watched = _this.data('watched');
            var url = '/api/v2/user/question-watches/{{$question['id']}}';
            var type = (watched == '1') ? 'DELETE' : 'PUT';

            axios({ method:type, url: url })
              .then(function (response) {
                if (watched == '1') {
                    _this.removeClass('button-grey').addClass('button-primary button-blue').text('关注');
                    _this.data('watched', '0');
                    $('#watchers_count_value').text(parseInt($('#watchers_count_value').text())-1);
                } else {
                    _this.removeClass('button-primary button-blue').addClass('button-grey').text('已关注');
                    _this.data('watched', '1');
                    $('#watchers_count_value').text(parseInt($('#watchers_count_value').text())+1);
                }
              })
              .catch(function (error) {
                showError(error.response.data);
              });
        });
        // 切换问题排序
        $('.zy_select').on('click', 'li', function() {
            var type = $(this).data('type');
            // 清空数据
            $('#question-answers-list').html('');
            scroll.init({
                container: '#question-answers-list',
                loading: '.question-main-l',
                url: '/question/{{$question['id']}}/answers',
                paramtype: 1,
                params: {order_type: type, limit: 10}
            });
        });

        $('#write-answer').on('click', function () {
            checkLogin();
            $('.question-main-r').show();
            $('html,body').animate({scrollTop:$('.question-main-r').offset().top}, 500);
            window.editor.setCursor({line:0, ch:0});
            var _targetTop = $('.question-main-r').offset().top;//获取位置
            jQuery("html,body").animate({scrollTop:_targetTop},300);//跳转
        });
        $('.show-share').on('click', function () {
            var _this = $(this);
            _this.siblings('.share-show').stop().fadeToggle();
        });
        // 回答问题
        $('#answer-send').on('click', function () {
            if (checkSubmitFlg) {
                noticebox('请勿重复提交', 0);

                return false;
            }
            checkSubmitFlg = true;
            var args = {
                'body': editor.value(),
                'anonymity': $("input[type='checkbox'][name='anonymity']:checked").val() == 'on' ? 1 : 0
            };
            if (args.body == '') {
                noticebox('回答内容不能为空', 0);

                return false;
            }
            axios.post('/api/v2/questions/{{$question['id']}}/answers', args)
              .then(function (response) {
                noticebox(response.data.message, 1, '/question/{{ $question['id'] }}');
              })
              .catch(function (error) {
                showError(error.response.data);
                checkSubmitFlg = false;
              });
        });

        $('#comment-button').on('click', function(){
            $('.detail_comment').show();
            $('.question-main-l').hide();
            $('.question-main-r').hide();
            scroll.init({
                container: '.J-commentbox',
                loading: '.detail_comment',
                url: '/question/{{$question->id}}/comments' ,
                canload: true
            });
        });

        $('#answer-button').on('click', function () {
            $('.question-main-l').show();
            $('.detail_comment').hide();
            $('.question-main-r').hide();
            scroll.init({
                container: '#question-answers-list',
                loading: '.question-main-l',
                url: '/question/{{$question['id']}}/answers',
                paramtype: 1,
                params: {order_type: 'default', limit: 10}
            });
        });
    </script>
@endsection