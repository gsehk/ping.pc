<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class PassportController extends BaseController
{
    public function index()
    {
        $this->view['site']['footer'] = 'simple';
        
    	return view('passport.login', $this->view);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('pc'));
    }
}
