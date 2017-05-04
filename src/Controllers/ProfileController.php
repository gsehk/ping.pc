<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
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

        dump($data->toArray());
        return view('profile.'.$page, ['page' => $page, 'data' => $data], $this->mergeData);
    }

    public function score(Request $request)
    {
        $type = $request->input('type') ?: '1';

        return view('profile.scoredetail', ['type' => $type]);
    }

}
