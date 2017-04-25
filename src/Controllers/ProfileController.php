<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class ProfileController extends BaseController
{
    public function feedAll(Request $request)
    {
        return view('profile.feedall');
    }
}
