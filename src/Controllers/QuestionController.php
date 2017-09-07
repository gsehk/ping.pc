<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class QuestionController extends BaseController
{

    public function index()
    {
        $issues = createRequest('GET', '/api/v2/questions', ['limit' => 9]);
        // dd($issues->toArray());
        $data['issues'] = $issues;

        return view('pcview::question.index', $data, $this->PlusData);
    }

}