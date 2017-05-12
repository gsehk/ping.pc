<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;

class BaseController extends Controller
{
	protected $mergeData;

    use AuthenticatesUsers {
        login as traitLogin;
    }

    public function __construct()
    {
    	$this->middleware(function($request, $next){
			$this->mergeData['user'] = $this->guard()->user() ?: null;

			if ($this->mergeData['user']) {
				// 用户信息
				$user_profile = User::where('id', '=', $this->mergeData['user']['id'])->with('datas', 'counts')->get();

				foreach ($user_profile[0]['datas'] as $key => $value) {
					$user_profile[0][$value['profile']] = $value['pivot']['user_profile_setting_data'];
				}
				unset($user_profile[0]['datas']);
				$this->mergeData['user'] = $user_profile[0];

				// 用户积分
				$this->mergeData['user']['credit'] = CreditUser::where('user_id', $this->mergeData['user']['id'])->value('score');

				// token
				$this->mergeData['user']['token'] = AuthToken::where('user_id', $this->mergeData['user']['id'])->select('token')->first();
				
	            // user role
	            $this->mergeData['user']['role'] = DB::table('role_user')->where('user_id', $this->mergeData['user']['id'])->first();
			}

			// 站点配置
	        $config = [
	            'title' => 'ThinkSNS Plus Title',
	            'keywords' => 'ThinkSNS Plus Keywords',
	            'description' => 'ThinkSNS Plus Description',	
	            'nav' => ['feed'=>route('pc:feed'), 'news'=>route('pc:news')]
	        ];
	        $this->mergeData['site'] = $config;

            $this->mergeData['routes']['storage'] = '/api/v1/storages/';
    		return $next($request);
    	});
    }

}
