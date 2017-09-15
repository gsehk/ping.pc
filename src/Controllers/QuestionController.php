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

}