<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Http\Controllers\Controller;

class TestController extends Controller
{
	public function show()
	{
		$res = Followed::paginate(3);

		return view('pcview::test', [
			'res' => $res,
		]);
	}
}
