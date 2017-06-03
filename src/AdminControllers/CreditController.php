<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditRecord;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditSetting;

/**
* 积分规则
*/
class CreditController extends Controller
{
    
    public function index(Request $request)
    {
        $key = $request->key;
        $datas = CreditSetting::where(function ($query) use ($key) {
                if ($key) {
                    $query->where('alias', 'like', '%'.$key.'%');
                }
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
    }

    public function handleCreditRule(Request $request)
    {
        /*$this->validate($request, [
            'name' => 'bail|required|max:50',
            'alias' => 'bail|required|max:50',
            'type' => 'bail|nullable|alpha',
            'cycle' => 'bail|nullable|alpha',
            'cycle_times' => 'bail|required|integer',
            'score' => 'bail|required|integer'
        ]);*/
        $id = $request->id ?? 0;
        $setting = CreditSetting::find($id);
        if (!$setting) {
            $setting = new CreditSetting();
        }
        $setting->name = $request->name;
        $setting->alias = $request->alias;
        $setting->cycle_times = $request->cycle_times;
        $setting->type = $request->type ?: '';
        $setting->cycle = $request->cycle ?: '';
        $setting->des = $request->des ?: '';
        $setting->info = $request->info ?: '';
        $setting->score = $request->score;
        $setting->save();

        return response()->json(static::createJsonData([
            'status'  => true,
            'message' => '操作成功',
        ]))->setStatusCode(200);
    }

    public function delSet(int $cid)
    {
        $setting = CreditSetting::find($cid);
        if ($setting) {
            $setting->delete();

            return response()->json(static::createJsonData([
                'status'  => true,
                'message' => '操作成功',
            ]))->setStatusCode(200);
        }
    }

    
}