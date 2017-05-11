<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Zhiyi\Plus\Models\AuthToken;

class BaseController extends Controller
{
	protected $mergeData;

    use AuthenticatesUsers {
        login as traitLogin;
    }

    public function __construct()
    {
    	$this->middleware(function($request, $next){
    		// 用户信息
			$this->mergeData['user'] = $this->guard()->user() ?: null;

			// token
			$this->mergetData['user']['token'] = AuthToken::where('user_id', $this->mergeData['user']['id'])->select('token')->first();
			
            // user role
            $this->mergeData['user']['role'] = DB::table('role_user')->where('user_id', $this->mergeData['user']['id'])->first();

			// 站点配置
	        $config = [
	            'title' => 'ThinkSNS Plus Title',
	            'keywords' => 'ThinkSNS Plus Keywords',
	            'description' => 'ThinkSNS Plus Description',	
	            'nav' => ['feed'=>route('pc:feed'), 'news'=>route('pc:news')]
	        ];
	        $this->mergeData['site'] = $config;

            $this->mergeData['route']['storage'] = '/api/v1/storages/';
    		return $next($request);
    	});
    }

}
