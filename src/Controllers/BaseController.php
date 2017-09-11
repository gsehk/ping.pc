<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class BaseController extends Controller
{
	protected $PlusData;

    public function __construct()
    {
    	$this->middleware(function($request, $next){
            // user info
    		$this->PlusData['token'] = session('token') ?: '';

    		$this->PlusData['TS'] = null;
    		if ($this->PlusData['token']) {
    			$this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                if (isset($this->PlusData['TS']['statusCode'])) { // 失败刷新token
                    $this->PlusData['TS'] = createRequest('GET', '/api/v2//tokens/', ['token' => $this->PlusData['token']]);
                }
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
    		}
            
			// site config
	        $config = [];
	        $this->PlusData['site']['conifg'] = $config;

	        // common config
            $app_url = getenv('APP_URL');
            $this->PlusData['routes']['siteurl'] = getenv('APP_URL');
            $this->PlusData['routes']['api'] = $app_url . '/api/v2';
            $this->PlusData['routes']['storage'] = $app_url . '/api/v2/files/';
            $this->PlusData['routes']['resource'] = asset('');

    		return $next($request);
    	});
    }
}

