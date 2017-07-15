<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getShort;

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
				$user = User::where('id', '=', $this->mergeData['TS']['id'])
									->with('datas', 'counts')
									->first();

				$this->mergeData['TS'] = $this->formatUserDatas($user);

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

	        // 公共配置
            $this->mergeData['routes']['storage'] = '/api/v2/files/';
            $this->mergeData['routes']['resource'] = asset('');

            // Socket地址
	        $imserviceconfig = CommonConfig::byNamespace('common')->byName('im:serve')->first();
	        $imserviceconfig->value = '119.23.200.80';
	        $this->mergeData['routes']['socket'] = 'http://' . $imserviceconfig->value;

    		return $next($request);
    	});
    }


    public function formatUserDatas($user)
    {
    	if (!$user) {
    		return false;
    	}
    	$rs['id'] = $user->id;
    	$rs['name'] = $user->name;
    	$rs['phone'] = $user->phone;
    	$rs['email'] = $user->email;

    	// 用户信息
		if (!empty($user->datas)) {
	        foreach ($user->datas as $value) {
	            $rs[$value->profile] = $value->pivot->user_profile_setting_data;;
	        }
		}
		// 默认头像
		$rs['avatar'] = empty($rs['avatar']) ? asset('images/avatar.png') : '/api/v2/files/' . $rs['avatar'];
		$rs['sex'] = empty($rs['sex']) ? 3 : $rs['sex'];

		// 统计信息
		if (!empty($user->counts)) {
    		// 统计信息 
            foreach ($user->counts as $key => $value) {
                $rs[$value->key] = $value->value;
            }
		}
		if (!$user->user_verified) {
			$user->user_verified = UserVerified::byAudit()
								->where('user_id', $user->id)
								->first();
			$rs['user_verified'] = $user->user_verified ?: "";
		}
		return $rs;
    }

    public function getTime($date)
    {
    	// 本地化
    	Carbon::setLocale('zh');

    	// 一小时内显示文字
    	if (Carbon::now()->subHours(1) > Carbon::parse($date)) {
            return Carbon::parse($date)->toDateTimeString();
        }

        return Carbon::parse($date)->diffForHumans();
    }
}
