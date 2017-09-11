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
            // 用户信息
    		$this->PlusData['token'] = session('token') ?: '';

    		$this->PlusData['TS'] = null;
    		if ($this->PlusData['token']) {
    			$this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                if (!$this->PlusData['TS']->isSuccessful()) { // 不成功跳至登录重新获取授权
                    // 刷新授权
                    $token = createRequest('PATCH', '/api/v2/tokens/' . $this->PlusData['token']);
                    if (!$this->PlusData['TS']->isSuccessful()) { // 刷新授权失败跳至登录页
                        return redirect(route('pc:login'));
                    } else { // 重新获取用户信息
                        session('token', $token['token']);
                        $this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                    }
                }
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
    		}
            
			// 站点配置
	        $config = [];
	        $this->PlusData['site']['conifg'] = $config;

	        // 公共配置
            $app_url = getenv('APP_URL');
            $this->PlusData['routes']['siteurl'] = getenv('APP_URL');
            $this->PlusData['routes']['api'] = $app_url . '/api/v2';
            $this->PlusData['routes']['storage'] = $app_url . '/api/v2/files/';
            $this->PlusData['routes']['resource'] = asset('');

    		return $next($request);
    	});
    }
}

