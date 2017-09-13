<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class BaseController extends Controller
{
	protected $PlusData;

    public function __construct()
    {
    	$this->middleware(function($request, $next){
            // 用户信息
    		$this->PlusData['token'] = Session::get('token');

    		$this->PlusData['TS'] = null;
    		if ($this->PlusData['token']) {
    			$this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                if (!isset($this->PlusData['TS']['id'])) { // 不成功跳至登录重新获取授权
                    // 刷新授权
                    $token = createRequest('PATCH', '/api/v2/tokens/' . $this->PlusData['token']);
                    if (!isset($token['token'])) { // 刷新授权失败跳至登录页
                        Session::flush();
                        return redirect(route('pc:login'));
                    } else { // 重新获取用户信息
                        Session::put('token', $token['token']);
                        $this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                    }
                }
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
    		}
            
			// 站点配置
            $config = Cache::get('config');

            if (!$config) {
                $config = [];

                // 启动信息
                $config['bootstrappers'] = createRequest('GET', '/api/v2/bootstrappers/');

                // 缓存配置信息
                Cache::forever('config', $config);
            }

	        $this->PlusData['site']['conifg'] = $config;

	        // 公共地址
            $app_url = getenv('APP_URL');
            $this->PlusData['routes']['siteurl'] = getenv('APP_URL');
            $this->PlusData['routes']['api'] = $app_url . '/api/v2';
            $this->PlusData['routes']['storage'] = $app_url . '/api/v2/files/';
            $this->PlusData['routes']['resource'] = asset('');

    		return $next($request);
    	});
    }
}

