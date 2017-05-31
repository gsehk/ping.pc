<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditRecord;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditSetting;

class CreditController extends BaseController
{
    public function index(Request $request)
    {
        $user_id = $this->mergeData['TS']['id'] ?? 0;
        $type = $request->input('type') ?: '1';
        switch ($type) {
            case 2:
                $data = CreditUser::where('user_id', $user_id)->first();
                $setting = CreditSetting::orderBy('id', 'DESC')->paginate(10);
                $data['setting'] = $setting;
                $data['page'] = $setting->appends(['type'=>$type])->links('pcview::template.page');
                break;
            default:
                $data = CreditUser::where('user_id', $user_id)->first();
                $record = CreditRecord::byUserId($user_id)->orderBy('id', 'DESC')->paginate(10);
                $data['record'] = $record;
                $data['page'] = $record->appends(['type'=>$type])->links('pcview::template.page');
                break;
        }
        
        $data['type'] = $type;

        $data['ischeck'] = CheckInfo::where('created_at', '>', Carbon::today())
                            ->where(function($query) use ($user_id) {
                                if ($this->mergeData) {
                                    $query->where('user_id', $user_id);
                                }
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();
        $data['checkin'] = CheckInfo::where(function($query) use ($user_id){
                                if ($this->mergeData) {
                                    $query->where('user_id', $user_id);
                                }
                            })
                            ->first();

        return view('pcview::credit.index', $data, $this->mergeData);
    }
}
