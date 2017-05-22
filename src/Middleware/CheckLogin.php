<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware;

use Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;

class CheckLogin
{
    use AuthenticatesUsers {
        login as traitLogin;
    }


    public function handle(Request $request, Closure $next)
    {   
        if (!$this->guard()->check()) {
            $history = '/' . Route::getCurrentRoute()->uri;
            Session::put('history', $history);
            return redirect(route('pc:index'));
        }
        
        return $next($request);
    }
}
