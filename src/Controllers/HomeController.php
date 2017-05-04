<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
    	$data['type'] = 1;
    	return view('home.index', $data, $this->mergeData);
    }
}
