<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceImage;

class QuestionController extends BaseController
{

    public function question(Request $request)
    {
        $this->PlusData['current'] = 'question';
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

    public function topicInfo(Request $request, int $topic)
    {
        $data['topic'] = createRequest('GET', '/api/v2/question-topics/'.$topic );
        //dd($data['topic']->toArray());

        return view('pcview::question.topic_info', $data, $this->PlusData);
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
        $question->body = replaceImage($question->body);
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
        $answer = createRequest('GET', '/api/v2/question-answers/'.$answer );
        $answer->collect_count =  $answer->collectors->count();
        $data['answer'] = $answer;

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

    public function createQuestion(Request $request, int $question_id = 0)
    {
        if ($question_id > 0) {
            $data['question'] = createRequest('GET', '/api/v2/questions/'.$question_id );
        }

        $data['topics'] = createRequest('GET', '/api/v2/question-topics');

        return view('pcview::question.create_question', $data, $this->PlusData);
    }

    public function getUsers(Request $request)
    {
        $ajax = $request->input('ajax');
        $params['limit'] = $request->input('limit') ?: 10;
        $params['topics'] = is_array($request->input('topics')) ? implode(',', $request->input('topics')) : $request->input('topics') ?: '';
        $params['keyword'] = $request->input('keyword') ?: '';
        $data['topics'] = $params['topics'];
        if ($ajax == 1) {
            $data['users'] = createRequest('GET', '/api/v2/question-experts', $params);
            $return = view('pcview::question.user_list', $data)
                ->render();

            return response()->json([
                'status'  => true,
                'data' => $return,
            ]);
        } else {
            return view('pcview::question.users', $data, $this->PlusData);
        }
    }

    public function getAnswers(Request $request, int $question_id)
    {
        $params['limit'] = $request->input('limit') ?: 10;
        $params['offset'] = $request->input('offset') ?: 0;
        $params['order_type'] = $request->input('order_type') ?: 'default';
        $data['answers'] = createRequest('GET', '/api/v2/questions/'.$question_id.'/answers', $params);
        $question = createRequest('GET', '/api/v2/questions/'.$question_id );
        if (!empty($question['adoption_answers'])) { // 采纳回答
            $question['adoption_answers']->each(function ($item, $key) use ($data) {
                $data['answers']->prepend($item);
            });
        }
        if (!empty($question['invitation_answers'])) { // 悬赏人回答
            $question['invitation_answers']->each(function ($item, $key) use ($data) {
                $item->invitation = 1;
                $data['answers']->prepend($item);
            });
        }
        $return = view('pcview::question.question_answer', $data, $this->PlusData)
            ->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
        ]);
    }

    /**
     * 问题评论列表.
     *
     * @param  int    $question 问题id
     * @return mixed
     */
    public function questionComments(Request $request, int $question)
    {
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 10),
        ];
        $comments = createRequest('GET', '/api/v2/questions/'.$question.'/comments', $params);
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