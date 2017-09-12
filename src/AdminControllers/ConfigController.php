<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class ConfigController extends Controller
{

	public function index()
	{
		echo 111;
	}

	public function getnav(Request $request)
	{
		$nid = $request->query('nid');

		echo $nid;
	}


	public function manage(){}
}