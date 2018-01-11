<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware;

use Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {   
        // 跳转登录
        if ( !Session::get('token') ) {
            // 设置当前页面
            $history = getenv('APP_URL') . '/' . $request->getRequestUri();
            Session::put('history', $history);
            return redirect(route('login'));
        }
        
        return $next($request);
    }
}
