<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class PassportController extends BaseController
{
    public function index()
    {
        
    	return view('passport.login', $this->view);
    }

    public function register(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('passport.register', ['type'=>$type]);
    }

    public function perfect()
    {

        return view('passport.perfect');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('pc'));
    }

    public function findPassword(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('passport.findpwd', ['type' => $type]);
    }
}
