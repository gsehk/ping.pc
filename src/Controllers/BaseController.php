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
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class BaseController extends Controller
{
	protected $mergeData;

    public function __construct()
    {
    	$this->middleware(function($request, $next){
    		$this->mergeData['token'] = session('token') ?: '';
    		$this->mergeData['mid'] = session('mid') ?: '';

    		$this->mergeData['TS'] = null;
    		if ($this->mergeData['mid']) {
    			$this->mergeData['TS'] = createRequest('GET', '/api/v2/users/' . $this->mergeData['mid']);
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
            $this->mergeData['routes']['app_url'] = getenv('APP_URL');

    		return $next($request);
    	});
    }
}
