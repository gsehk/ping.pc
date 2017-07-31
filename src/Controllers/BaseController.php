<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Session;
use Carbon\Carbon;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Role;
use Zhiyi\Plus\Models\User;
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\CommonConfig;
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
    		$this->PlusData['mid'] = session('mid') ?: '';

    		$this->PlusData['TS'] = null;
    		if ($this->PlusData['mid']) {
    			$this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
    		}

			// site config
	        $config = [
	            'nav' => ['feed'=>route('pc:index'), 'news'=>route('pc:news')]
	        ];
	        $this->PlusData['site'] = $config;

	        // common config
            $app_url = getenv('APP_URL');
            $this->PlusData['routes']['siteurl'] = getenv('APP_URL');
            $this->PlusData['routes']['api'] = $app_url . '/api/v2';
            $this->PlusData['routes']['storage'] = $app_url . '/api/v2/files/';
            $this->PlusData['routes']['resource'] = asset('');

    		return $next($request);
    	});
    }

    public function RequestApi($url = '', $method = 'GET', $params = array())
    {   
        $request = Request::create($url, $method, $params);
        $request->headers->add(['Accept' => 'application/json','Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly90c3MuaW8vYXBpL3YyL3Rva2VucyIsImlhdCI6MTUwMTA2MDIzNiwiZXhwIjoxNTAyMjY5ODM2LCJuYmYiOjE1MDEwNjAyMzYsImp0aSI6InBEdnhFSmhSMFF0STJ1TlAifQ.KM_HPX62sBnBqPNZtHcxjNnDOibjXVcr6jVCcreQntU']);
        
        $response = Route::dispatch($request)->exception ?: Route::dispatch($request)->original;
        
        return $response;
    }
}

