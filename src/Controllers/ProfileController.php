<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class ProfileController extends BaseController
{

    public function index(Request $request)
    {
        $type = $request->input('type') ?: 'all';

        return view('profile.index', ['type' => $type]);
    }

    public function article(Request $request)
    {
        $type = $request->input('type') ?: 'relase';

        return view('profile.article', ['type' => $type]);
    }

    public function collection(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('profile.collection', ['type' => $type]);
    }

    public function myFans(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('profile.myfans', ['type' => $type]);
    }

    public function rank()
    {

        return view('profile.rank');
    }

    public function account(Request $request)
    {
        $page = $request->input('page') ?: 'account';
        $data = User::where('id', $this->mergeData['user']->id)
                ->select('id', 'name')
                ->with('datas')
                ->first();

        foreach ($data['datas'] as $key => &$value) {
            $data[$value['profile']] = $value['pivot']['user_profile_setting_data'];
        }
        unset($data['datas']);

        return view('profile.'.$page, ['page' => $page, 'data' => $data], $this->mergeData);
    }

    public function score(Request $request)
    {
        $type = $request->input('type') ?: '1';

        return view('profile.scoredetail', ['type' => $type]);
    }


    public function doSaveAuth(Request $request)
    {
        $isVerif = UserVerified::where('uid', $this->mergeData['user']->id)
                    ->count();
        if ($isVerif) {
            return response()->json([
                'status' => false,
                'message' => '您已提交认证资料,请勿重复提交',
            ])->setStatusCode(202);
        }
        if (!$request->realname) {
            return response()->json([
                'status' => false,
                'message' => '真实姓名不能为空',
            ])->setStatusCode(201);
        }
        if (!$request->phone) {
            return response()->json([
                'status' => false,
                'message' => '联系方式错误',
            ])->setStatusCode(201);
        }
        if (!$request->idcard) {
            return response()->json([
                'status' => false,
                'message' => '身份证号码错误',
            ])->setStatusCode(201);
        }

        $verif = new UserVerified();

        $verif->uid = $this->mergeData['user']->id;
        $verif->realname = $request->realname;
        $verif->idcard = $request->idcard;
        $verif->phone = $request->phone;
        $verif->info = $request->info ?: '';
        $verif->storage = $request->task_id ?: '';
        $verif->save();

        return response()->json([
            'status' => true
        ])->setStatusCode(200);        
    }
}
