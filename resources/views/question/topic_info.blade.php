@section('title') 问答 @endsection

@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp

@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/question.css') }}" />
@endsection

@section('content')
    <div class="question_left_container">
        <div class="question-topic">
            <div class="topic-info">
                <div class="info-left">
                    <img src="{{ $topic->avatar or asset('assets/pc/images/default_picture.png') }}" width="120px" height="120px">
                </div>
                <div class="info-right">
                    <div class="topic-title">{{ $topic->name }}</div>
                    <div class="topic-foot">
                        <div class="foot-count">
                            <span class="count">关注 <font class="mcolor" id="tf-count-{{ $topic->id }}">{{ $topic->follows_count }}</font></span>
                            <span class="count">问题 <font class="mcolor">{{ $topic->questions_count }}</font></span>
                        </div>
                    </div>
                    @if($topic->has_follow)
                        <div class="has-follow" data-id="{{ $topic->id }}" data-status="1">已关注</div>
                    @else
                        <div class="has-follow add-follow" data-id="{{ $topic->id }}" data-status="0">+ 关注</div>
                    @endif

                    {{-- 第三方分享 --}}
                    <div class="topic-share">
                        <button class="button button-plain show-share" type="button">
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fenxiang1"></use></svg>
                            分享
                        </button>
                        <div class="share-show">
                            分享至：
                            @php
                                // 设置第三方分享图片
                               $share_pic = $topic->avatar ? $topic->avatar : asset('assets/pc/images/default_picture.png');
                            @endphp
                            @include('pcview::widgets.thirdshare' , ['share_url' => route('pc:topicinfo', ['topic' => $topic->id]), 'share_title' => $topic->name, 'share_pic' => $share_pic])
                            <div class="triangle"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="topic-description">
                <span class="intro">话题简介：</span>
                <span class="h-d">{!! str_limit($topic->description, 250, '...') !!}</span>
                <span class="s-d">{{ $topic->description }}</span>
                @if(strlen($topic->description) > 250)
                    &nbsp; &nbsp; <a href="javascript:;" class="show-description" data-show="0">查看详情</a>
                @endif
            </div>
        </div>

        {{-- 问答 --}}
        <div class="question_body">
            <div class="question_sub_nav">
                <a class="active" href="#" data-type="hot">热门</a>
                <a href="#" data-type="excellent">精选</a>
                <a href="#" data-type="reward">悬赏</a>
                <a href="#" data-type="new">最新</a>
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
                                <img src="{{ getAvatar($user, 50) }}"/>
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
            <a href="javascript:;" onclick="question.create({{ $topic->id }})">
            <span>
                <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-publish"></use></svg>提问
            </span>
            </a>
        </div>

        {{-- 热门问题 --}}
        @include('pcview::widgets.hottopics')
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
    <script>
        $(function(){
            var topic_id = "{{ $topic->id }}";
            scroll.init({
                container: '#question-list',
                loading: '.question_body',
                url: '/question/topic/' + topic_id + '/question',
                paramtype:1,
                loadtype: 1,
                params: {type: 'hot', limit: 10, topic_id : topic_id}
            });
        })

        // 切换分类
        $('.question_sub_nav a').on('click', function() {
            var type = $(this).data('type');
            // 清空数据
            $('#question-list').html('');

            scroll.init({
                container: '#question-list',
                loading: '.question_body',
                url: '/question/topic/' + topic_id + '/question',
                paramtype:1,
                loadtype: 1,
                params: {type: type, limit: 10, topic_id : topic_id}
            });

            // 修改样式
            $('.question_sub_nav a').removeClass('active');
            $(this).addClass('active');
        });

        // 关注话题
        $('.question-topic').on('click', '.has-follow', function () {
            var _this = $(this);
            var topic_id = _this.data('id');
            var status = _this.data('status');
            var url = '/api/v2/user/question-topics/' + topic_id;
            var followCount = parseInt($('#tf-count-' + topic_id).text());
            if (status == 0) {
                axios.put(url)
                  .then(function (response) {
                    noticebox('操作成功', 1);
                    _this.removeClass('add-follow');
                    _this.data('status', 1);
                    _this.text('已关注');
                    $('#tf-count-' + topic_id).text(followCount + 1);
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            } else {
                axios.delete(url)
                  .then(function (response) {
                    noticebox('操作成功', 1);
                    _this.addClass('add-follow');
                    _this.data('status', 0);
                    _this.text('+ 关注');
                    $('#tf-count-' + topic_id).text(followCount - 1);
                  })
                  .catch(function (error) {
                    showError(error.response.data);
                  });
            }
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

        $('.show-share').on('click', function () {
            var _this = $(this);
            _this.siblings('.share-show').stop().fadeToggle();
        });

    </script>
@endsection