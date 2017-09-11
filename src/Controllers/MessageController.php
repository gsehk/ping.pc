<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class MessageController extends BaseController
{
	public function index(Request $request, $type)
	{
		$data['type'] = $type;

		return view('pcview::message.index', $data, $this->PlusData);
	}

    
}
