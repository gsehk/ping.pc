<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getShort;

class BaseController extends Controller
{
	protected $mergeData;

    public function __construct()
    {
    	$this->middleware(function($request, $next){

    		$token = session('token') ?: '';
    		$this->mergeData['token'] = $token;
    		$this->mergeData['TS'] = $token ? (app(JWTAuth::class)->toUser($token) ?: null) : null;

			if ($this->mergeData['TS']) {
	            // user role
	            $this->mergeData['TS']['role'] = DB::table('role_user')->where('user_id', $this->mergeData['TS']['id'])->first();
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

    		return $next($request);
    	});
    }
}
