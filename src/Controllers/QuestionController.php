<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class QuestionController extends BaseController
{

    public function question(Request $request)
    {
        if ($request->ajax()){
            
        }
        return view('pcview::question.index', [], $this->PlusData);
    }

    public function topic(Request $request)
    {
        return view('pcview::question.index', [], $this->PlusData);
    }

}