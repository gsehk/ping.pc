<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class InformationController extends BaseController
{
    public function index(Request $request)
    {
        return view('information.index');
    }

    public function read()
    {

        return view('information.read');
    }

    public function release()
    {

        return view('information.release');
    }
}
