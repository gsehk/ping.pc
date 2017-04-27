<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('home.index', ['type'=>$type]);
    }
}
