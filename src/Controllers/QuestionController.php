<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class QuestionController extends BaseController
{

    public function index()
    {
        $issues = createRequest('GET', '/api/v2/questions', ['limit' => 9]);

        $data['answerRank'] = [
            'day' => createRequest('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'day']),
            'week' => createRequest('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'week']),
            'month' => createRequest('GET', '/api/v2/question-ranks/answers', ['limit' => 5, 'type' => 'month']),
        ];

        $data['issues'] = $issues;

        return view('pcview::question.index', $data, $this->PlusData);
    }

}