<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Denounce;
use Zhiyi\Plus\Http\Controllers\Controller;

class FeedController extends BaseController
{
    public function denounce(Request $request)
    {
    	$denounce = Denounce::where([['from', $request->from], ['aid', $request->aid]])->first();
    	if (!$denounce) {
    		$source_url = '';
    		switch ($request->from) {
    			case 'weibo':
    				$source_url = '/home/'.$request->aid.'/feed';
    				break;
    		}
    		$denounce = new Denounce();
    		$denounce->from = $request->from;
    		$denounce->aid = $request->aid;
    		$denounce->user_id = $this->mergeData['TS']['id'];
    		$denounce->to_uid = $request->to_uid;
    		$denounce->reason = $request->reason;
    		$denounce->source_url = $source_url;
    		$denounce->save();

    		return response()->json([
                'status'  => true,
                'message' => '举报成功',
            ])->setStatusCode(200);
    	} else {

    		return response()->json([
                'status'  => false,
                'message' => '资源已被举报',
            ])->setStatusCode(200);
    	}
    }
}
