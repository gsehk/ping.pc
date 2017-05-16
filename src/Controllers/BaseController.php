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
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;

class BaseController extends Controller
{
	protected $mergeData;

    use AuthenticatesUsers {
        login as traitLogin;
    }

    public function __construct()
    {
    	$this->middleware(function($request, $next){
			$this->mergeData['TS'] = $this->guard()->user() ?: null;

			if ($this->mergeData['TS']) {
				// 用户信息
				$user_profile = User::where('id', '=', $this->mergeData['TS']['id'])->with('datas', 'counts')
									->get()
									->toArray();

				foreach ($user_profile[0]['datas'] as $key => $value) {
					$user_profile[0][$value['profile']] = $value['pivot']['user_profile_setting_data'];
				}
				unset($user_profile[0]['datas']);
				$this->mergeData['TS'] = $user_profile[0];

				// 用户积分
				$this->mergeData['TS']['credit'] = CreditUser::where('user_id', $this->mergeData['TS']['id'])
														->value('score');

				// token
				$this->mergeData['TS']['token'] = AuthToken::where('user_id', $this->mergeData['TS']['id'])
														->where('state', 1)
														->value('token');
				
	            // user role
	            $this->mergeData['TS']['role'] = DB::table('role_user')->where('user_id', $this->mergeData['TS']['id'])
	            										->first();
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
            $this->mergeData['routes']['resource'] = asset('');

    		return $next($request);
    	});
    }

}
