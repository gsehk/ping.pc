@section('title') 问答 @endsection
@extends('pcview::layouts.default')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('assets/pc/css/question.css') }}" />
@endsection

@section('content')
<div class="question_left_container">
    <div class="question_nav">
        <a class="active" href="{{ route('pc:question') }}">问答</a>
        <a href="{{ route('pc:topic') }}">话题</a>
    </div>

    {{-- 问答 --}}
    <div class="question_body">
        <div class="question_sub_nav">
            <a class="active" href="javascript:;" data-type="hot">热门</a>
            <a href="javascript:;" data-type="excellent">精选</a>
            <a href="javascript:;" data-type="reward">悬赏</a>
            <a href="javascript:;" data-type="new">最新</a>
            <a href="javascript:;" data-type="all">全部</a>
        </div>
        <div id="question-list" class="question_list"></div>
    </div>
    {{-- /问答 --}}
</div>

<div class="right_container">
    <div class="q_c_post_btn">
        <a href="javascript:;" onclick="question.create()">
            <span>
                <svg class="icon white_color" aria-hidden="true"><use xlink:href="#icon-publish"></use></svg>提问
            </span>
        </a>
    </div>
    {{-- 热门问题 --}}
    @include('pcview::widgets.hotquestions')

    {{-- 问答达人排行 --}}
    @include('pcview::widgets.questionrank')
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/pc/js/module.question.js') }}"></script>
<script>
    $(function(){
        scroll.init({
            container: '#question-list',
            loading: '.question_body',
            url: '/question',
            paramtype: 1,
            params: {type: 'hot', isAjax: true, limit: 10}
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
            url: '/question',
            paramtype: 1,
            params: {type: type, isAjax: true, limit: 10}
        });

        // 修改样式
        $('.question_sub_nav a').removeClass('active');
        $(this).addClass('active');
    });

</script>
@endsection