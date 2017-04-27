<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class ProfileController extends BaseController
{
    public function feedAll(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('profile.feedall', ['type'=>$type]);
    }

    public function myFeed()
    {

        return view('profile.myfeed');
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

        return view('profile.'.$page, ['page' => $page]);
    }

    public function score(Request $request)
    {
        $type = $request->input('type') ?: '1';

        return view('profile.scoredetail', ['type' => $type]);
    }

}
