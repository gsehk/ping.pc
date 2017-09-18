<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class QuestionController extends BaseController
{

    public function question(Request $request)
    {
        if ($request->ajax()){
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 0),
                'type' => $request->query('type', 'all'),
            ];
            $question['data'] = createRequest('GET', '/api/v2/questions', $params);

            $html = view('pcview::templates.question', $question, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'data' => $html
            ]);
        }

        return view('pcview::question.index', [], $this->PlusData);
    }

    public function topic(Request $request)
    {
        if ($request->ajax()){
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1:
                    $params = [
                        'offset' => $request->query('offset', 0),
                        'limit' => $request->query('limit', 10),
                        'follow' => $request->query('follow', 1),
                    ];
                    $data['data'] = createRequest('GET', '/api/v2/question-topics', $params);
                    $after = '';
                    break;
                case 2:
                    $params = [
                        'after' => $request->query('after', 0),
                        'limit' => $request->query('limit', 10),
                    ];
                    $questions = createRequest('GET', '/api/v2/user/question-topics', $params);
                    $questions->map(function($item){
                        $item->has_follow = true;
                    });
                    $question = clone $questions;
                    $after = $question->pop()->id ?? 0;
                    $data['data'] = $questions;
                    break;
            }
            $html = view('pcview::templates.topic', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        return view('pcview::question.topic', [], $this->PlusData);
    }

    /**
     * 问题详情.
     *
     * @param  int    $question_id
     * @return mixed
     */
    public function read(int $question_id)
    {
        $this->PlusData['current'] = 'question';

        $question = createRequest('GET', '/api/v2/questions/'.$question_id );
        // dd($question->toArray());
        $data['question'] = $question;

        return view('pcview::question.read', $data, $this->PlusData);

    }

    /**
     * 回答详情.
     *
     * @param  Request $request
     * @param  int     $answer  回答id
     * @return mixed
     */
    public function answer(int $answer)
    {
        $data['answer'] = createRequest('GET', '/api/v2/question-answers/'.$answer );
        // dd($data['answer']->toArray());

        return view('pcview::question.answer', $data, $this->PlusData);
    }

    /**
     * 回答评论列表.
     *
     * @param  int    $answer 回答id
     * @return mixed
     */
    public function answerComments(Request $request, int $answer)
    {
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 10),
        ];
        $comments = createRequest('GET', '/api/v2/question-answers/'.$answer.'/comments', $params);
        $comment = clone $comments;
        $after = $comment->pop()->id ?? 0;
        $data['comments'] = $comments;
        $html = view('pcview::templates.comment', $data, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]);
    }
}