<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\JWTCache as JWTCacheModel;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\asset;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;
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
                $config['bootstrappers']['wallet:ratio'] = ($config['bootstrappers']['wallet:ratio'] ?? 100) / 100 / 100;
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
    public function notice(int $status = 1, string $url = '', string $message = '', string $content = '', int $time = 5)
    {
        $data['status'] = $status;
        $data['message'] = $message;
        $data['content'] = $content;
        $data['url'] = $url;
        $data['time'] = $time;

        return view('pcview::templates.notice', $data, $this->PlusData);
    }

    /**
     * 查看资源页面
     *
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function reportView(Request $request)
    {
        $reportable_id = $request->query('reportable_id');
        $reportable_type = $request->query('reportable_type');
        if (! $reportable_id || ! $reportable_type) {
            return abort(404);
        }

        switch ($reportable_type) {
            case 'users':
                return response()->redirectTo(route('pc:mine', ['user' => $reportable_id]), 302);
                break;
            case 'comments': // 评论部分暂时跳转到所属资源的详情页
                $comment = CommentModel::find($reportable_id);
                if (! $comment) {
                    return abort(404);
                }
                return response()->redirectTo(route('pc:reportview', ['reportable_id' => $comment->commentable_id, 'reportable_type' => $comment->commentable_type]), 302);
                break;
            case 'feeds':
                return response()->redirectTo(route('pc:feedread', ['feed' => $reportable_id]), 302);
                break;
            case 'questions':
                return response()->redirectTo(route('pc:questionread', ['question_id' => $reportable_id]), 302);
                break;
            case 'question-answers':
                return response()->redirectTo(route('pc:answeread', ['answer' => $reportable_id]), 302);
                break;
            case 'news':
                return response()->redirectTo(route('pc:newsread', ['news_id' => $reportable_id]), 302);
                break;
            case 'groups':
                return response()->redirectTo(route('pc:groupread', ['group_id' => $reportable_id]), 302);
                break;
            default:
                return abort(404);
                break;
        }
    }
}

