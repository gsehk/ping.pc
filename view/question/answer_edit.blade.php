@section('title') 编辑回答 @endsection @extends('pcview::layouts.default') @section('styles')
    <link rel="stylesheet" href="{{ URL::asset('zhiyicx/plus-component-pc/css/question.css') }}" />
@endsection @section('content')
    <div class="create-question">
        <div class="question-tw">{{ $answer->question->subject }}</div>

        <div class="question-form-row">
            @include('pcview::widgets.markdown', ['height'=>'400px', 'width' => '100%', 'content' => $answer->body ?? ''])
        </div>
        <div class="question-form-row answer-from-row">

            <input id="anonymity" name="anonymity" type="checkbox" @if($answer->anonymity == 1) checked="checked" @endif class="input-checkbox"/>
            <label for="anonymity">启动匿名</label>

            <button class="edit-answer-btn" id="answer-submit">提交修改</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('zhiyicx/plus-component-pc/js/md5.min.js')}}"></script>
    <script>
        var answer_id = '{{ $answer->id }}';
        $('#answer-submit').on('click', function () {
            var args = {};
            args.body = editor.value();
            args.anonymity = $("input[type='checkbox'][name='anonymity']:checked").val() == 'on' ? 1 : 0;
            $.ajax({
                type: 'PATCH',
                url: '/api/v2/question-answers/' + answer_id,
                data: args,
                success: function(res, data, xml) {
                    if (xml.status == 201) {
                        noticebox(res.message, 1, '/question/answer/' + answer_id);
                    }
                },
                error: function (xml) {
                    showError(xml.responseJSON);
                }
            });
        });
    </script>
@endsection