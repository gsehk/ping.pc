<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;
use Zhiyi\Plus\Models\JWTCache as JWTCacheModel;
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
                // TODO 设置当前token为有效，临时解决方案
                JWTCacheModel::where('value', '"'. $this->PlusData['token'] . '"')->update([
                    'status' => 0,
                ]);

                $this->PlusData['TS'] = createRequest('GET', '/api/v2/user/');
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
            }

            // 站点配置
            $config = Cache::get('config');

            if (!$config) {
                $config = [];

                // 启动信息
                $config['bootstrappers'] = createRequest('GET', '/api/v2/bootstrappers/');
                $config['bootstrappers']['site']['reward']['amounts'] = $config['bootstrappers']['site']['reward']['amounts'] ?: '5,10,15';

                // 基本配置
                $repository = app(\Illuminate\Contracts\Config\Repository::class);
                $config['common'] = $repository->get('pc');

                // 顶部导航
                $config['nav'] = Navigation::byPid(0)->byPos(0)->get();

                // 底部导航
                $config['nav_bottom'] = Navigation::byPid(0)->byPos(1)->get();

                // 获取所有广告位
                $config['ads_space'] = createRequest('GET', '/api/v2/advertisingspace')->keyBy('space');

                // 缓存配置信息
                Cache::forever('config', $config);
            }

            $this->PlusData['config'] = $config;

            // 公共地址
            $app_url = getenv('APP_URL');
            $this->PlusData['routes']['siteurl'] = $app_url;
            $this->PlusData['routes']['api'] = $app_url . '/api/v2';
            $this->PlusData['routes']['storage'] = $app_url . '/api/v2/files/';
            $this->PlusData['routes']['resource'] = asset('');

            return $next($request);
        });
    }

    public function success($url, $message = '', $content = '', $time = 10)
    {

        return redirect(route('pc:success', ['status' => 1, 'message' => $message, 'content' => $content, 'url' => $url, 'time' => $time]));
    }

    public function error($url, $message = '', $content = '', $time = 10)
    {

        return redirect(route('pc:success', ['status' => 0, 'message' => $message, 'content' => $content, 'url' => $url, 'time' => $time]));
    }
}

