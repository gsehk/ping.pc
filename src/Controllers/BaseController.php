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
        // 初始化
        $this->middleware(function($request, $next){
            // 用户信息
            $this->PlusData['token'] = Session::get('token');

            $this->PlusData['TS'] = null;
            if ($this->PlusData['token']) {
                // TODO 设置当前token为有效，临时解决方案
                JWTCacheModel::where('value', '"'. $this->PlusData['token'] . '"')->update([
                    'status' => 0,
                ]);

                $this->PlusData['TS'] = createRequest('GET', '/api/v2/user/', [], 0);
                $this->PlusData['TS']['avatar'] = $this->PlusData['TS']['avatar'] ?: asset('images/avatar.png');
            }

            // 站点配置
            $config = Cache::get('config');

            if (!$config) {
                $config = [];

                // 启动信息接口
                $config['bootstrappers'] = createRequest('GET', '/api/v2/bootstrappers/', [], 0);
                $config['bootstrappers']['site']['reward']['amounts'] = '5,10,15';

                // 基本配置
                $repository = app(\Illuminate\Contracts\Config\Repository::class);
                $config['common'] = $repository->get('pc');

                // 顶部导航
                $config['nav'] = Navigation::byPid(0)->byPos(0)->orderBy('order_sort')->get();

                // 底部导航
                $config['nav_bottom'] = Navigation::byPid(0)->byPos(1)->get();

                // 获取所有广告位
                $config['ads_space'] = createRequest('GET', '/api/v2/advertisingspace', [], 0)->keyBy('space');

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


    /**
     * 操作提示
     * @author ZsyD
     * @param  int    $status  [状态]
     * @param  string $url     [跳转链接]
     * @param  string $message [信息]
     * @param  string $content [内容]
     * @param  int    $time    [跳转时间]
     * @return mixed
     */
    public function notice(int $status, string $url, string $message, string $content, int $time)
    {
        $data['status'] = $status;
        $data['message'] = $message;
        $data['content'] = $content;
        $data['url'] = $url;
        $data['time'] = $time;

        return view('pcview::templates.notice', $data, $this->PlusData);
    }
}

