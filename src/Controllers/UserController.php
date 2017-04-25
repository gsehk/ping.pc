<?php

namespace Zhiyicx\Component\ZhiyiPlus\PlusComponentWeb\Controllers;

use Zhiyicx\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentWeb\view;

class ExampleWebController extends Controller
{
    public function index()
    {
        return view('example');
    }

    public function admin()
    {
        return view('example');
    }
}
