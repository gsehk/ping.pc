<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditSetting;

class CreditController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?: '1';

        switch ($type) {
            case 2:
                $credit = CreditUser::where('user_id', $this->mergeData['TS']['id'])->first();
                $credit['setting'] = CreditSetting::simplePaginate(10);
                break;
            default:
                $credit = CreditUser::where('user_id', $this->mergeData['TS']['id'])
                    ->with('record')
                    ->first();
                break;
        }
        
        $credit['type'] = $type;
        
        return view('pcview::credit.index', $credit, $this->mergeData);
    }
}
