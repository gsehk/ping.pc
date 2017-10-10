@section('title') 问答 @endsection
@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
@endsection

@section('content')
    <div class="question_left_container">
        <div class="question-topic">
            <div class="topic-info">
                <div class="info-left">
                    <img src="{{ $topic->avatar }}" width="120px" height="120px">
                </div>
                <div class="info-right">
                    <div class="topic-title">{{ $topic->name }}</div>
                    <div class="topic-foot">
                        <div class="foot-count">
                            <span class="count">关注 <font class="mcolor">{{ $topic->follows_count }}</font></span>
                            <span class="count">问题 <font class="mcolor">{{ $topic->questions_count }}</font></span>
                        </div>
                    </div>
                    @if($topic->has_follow)
                        <div class="has-follow" data-id="{{ $topic->id }}" data-status="1">已关注</div>
                    @else
                        <div class="has-follow add-follow" data-id="{{ $topic->id }}" data-status="0">+ 关注</div>
                    @endif
                </div>
            </div>
            <div class="topic-description">
                <span class="intro">话题简介：</span>
                <span class="h-d">{!! str_limit($topic->description, 250, '...') !!}</span>
                <span class="s-d">{{ $topic->description }}</span>
                @if(mb_strlen($topic->description,'UTF8') > 250)
                    &nbsp; &nbsp; <a href="javascript:;" class="show-description" data-show="0">查看详情</a>
                @endif
            </div>
        </div>

        {{-- 问答 --}}
        <div class="question_body">
            <div class="question_sub_nav">
                <a href="#" data-type="hot">热门</a>
                <a href="#" data-type="excellent">精选</a>
                <a href="#" data-type="reward">悬赏</a>
                <a class="active" href="#" data-type="new">最新</a>
                <a href="#" data-type="all">全部</a>
            </div>
            <div id="question-list" class="question_list"></div>
        </div>
        {{-- /问答 --}}
    </div>

    <div class="right_container">
        {{-- 相关专家 --}}
        @if($topic->experts->count() > 1)
            <div class="recusers">
                <div class="experts-users-title">
                    <div>相关专家</div>
                </div>
                <ul>
                    @foreach ($topic->experts as $user)
                        <li>
                            <a href="{{ route('pc:mine', $user['id']) }}">
                                <img src="{{ $user['avatar'] or asset('zhiyicx/plus-component-pc/images/avatar.png') }}?s=50"/>
                            </a>
                            <span>
                                <a href="{{ route('pc:mine', $user['id']) }}">{{ $user['name'] }}</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
                @if ($topic->experts->count() >= 9)
                    <a class="recmore" href="{{ route('pc:topicexpert', $topic->id) }}">更多相关专家</a>
                @endif
            </div>
        @endif

        {{-- 提问 --}}
        <div class="q_c_post_btn">
            <a href="{{ route('pc:createquestion') }}">
            <span>
                <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-feiji"></use></svg>提问
            </span>
            </a>
        </div>

        {{-- 热门问题 --}}
        @include('pcview::widgets.hottopics')
    </div>
@endsection

@section('scripts')
    <script>
        var topic_id = "{{ $topic->id }}";
        setTimeout(function() {
            scroll.init({
                container: '#question-list',
                loading: '.question_body',
                url: '/question/topic/' + topic_id + '/question',
                paramtype:1,
                loadtype: 1,
                params: {type: 'new', limit: 10, topic_id : topic_id}
            });
        }, 300);

        // 切换分类
        $('.question_sub_nav a').on('click', function() {
            var type = $(this).data('type');
            // 清空数据
            $('#question-list').html('');

            setTimeout(function() {
                scroll.init({
                    container: '#question-list',
                    loading: '.question_body',
                    url: '/question/topic/' + topic_id + '/question',
                    paramtype:1,
                    loadtype: 1,
                    params: {type: type, limit: 10, topic_id : topic_id}
                });
            }, 300);

            // 修改样式
            $('.question_sub_nav a').removeClass('active');
            $(this).addClass('active');
        });

        // 关注话题
        $('.question-topic').on('click', '.has-follow', function () {
            var _this = $(this);
            var topic_id = _this.data('id');
            var status = _this.data('status');
            var url = API + '/user/question-topics/' + topic_id;
            if (status == 0) {
                $.ajax({
                    url: url,
                    type: 'PUT',
                    success: function(res, data, xml) {
                        if (xml.status == 201) {
                            noticebox('操作成功', 1);
                            _this.removeClass('add-follow');
                            _this.data('status', 1);
                            _this.text('已关注');
                        }
                    },
                    error: function(xhr){
                        showError(xhr.responseJSON);
                    }
                })
            } else {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(res, data, xml) {
                        if (xml.status == 204) {
                            noticebox('操作成功', 1);
                            _this.addClass('add-follow');
                            _this.data('status', 0);
                            _this.text('+ 关注');
                        }
                    },
                    error: function(xhr){
                        showError(xhr.responseJSON);
                    }
                })
            }
            console.log($(this).data())
        });
        
        $('.show-description').on('click', function () {
            if ($(this).data('show') == 0) {
                $('.h-d').hide();
                $('.s-d').show();
                $(this).data('show', 1);
                $(this).text('收起');
            } else {
                $('.s-d').hide();
                $('.h-d').show();
                $(this).data('show', 0);
                $(this).text('查看详情');
            }
        });

    </script>
@endsection