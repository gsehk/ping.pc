<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;


use Session;
use Overtrue\Socialite\SocialiteManager;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\socialiteRequest;

class SocialiteController extends BaseController
{
    /**
     * 三方登录/绑定（未登录时）.
     * @param Request $request
     * @param $service
     */
    public function redirectToProvider(Request $request, $service)
    {
        $config[$service] = $this->PlusData['config']['common'][$service];
        $config[$service]['redirect'] = $this->PlusData['routes']['siteurl'].'/socialite/'.$service.'/callback';

        if (!$config) {

            dd('暂未开通'.$service.'登录');
        }

        $socialite = new SocialiteManager($config);

        $response = $socialite->driver($service)->redirect();

        $response->send();
    }

    /**
     * 三方登录/绑定（已登录时）.
     * @param Request $request
     * @param $service
     * @author zuo
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\think\response\Redirect
     */
    public function redirectToProviderByBind(Request $request, $service)
    {
        if ($this->PlusData['TS']->phone == null) {

            return $this->error(Route('pc:binds'), '绑定失败', '绑定第三方账号必须绑定手机号码');
        }
        $config[$service] = $this->PlusData['config']['common'][$service];
        $config[$service]['redirect'] = $this->PlusData['routes']['siteurl'].'/socialite/'.$service.'/callback?type=bind';

        if (!$config) {

            dd('暂未开通'.$service.'登录');
        }

        $socialite = new SocialiteManager($config);

        $response = $socialite->driver($service)->redirect();

        $response->send();
    }

    /**
     * 第三方回调页.
     * @param Request $request
     * @param $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */

    public function handleProviderCallback(Request $request, $service)
    {
        $config[$service] = $this->PlusData['config']['common'][$service];
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $config[$service]['redirect'] = $this->PlusData['routes']['siteurl'].'/socialite/'.$service.'/callback'.($type != '' ? '?type=bind' : '');

        $socialite = new SocialiteManager($config);
        $user = $socialite->driver($service)->user();
        $access_token = $user->getToken()->access_token;

        // 已登录时账号绑定
        if ($type == 'bind') {
            $res = socialiteRequest('PATCH', '/api/v2/user/socialite/'.$service, ['access_token' => $access_token]);

            return isset($res['message'])
                ? $this->error(Route('pc:binds'), '绑定失败', $res['message'])
                : $this->success(Route('pc:binds'), '绑定成功', '您的账号已成功绑定');

        } else {
        // 未登录时账号注册/绑定

            $res = socialiteRequest('POST', '/api/v2/socialite/'.$service, ['access_token' => $access_token]);
            Session::put('initial_password', $res['user']['initial_password']);

            if (isset($res['token'])) { // 登录

                return redirect(route('pc:token', ['token' => $res['token'], 'type' => 0]));

            } elseif (isset($res['message']) && $res['message'] == '请绑定账号') { // 绑定、注册

                $data['other_type'] = $service;
                $data['access_token'] = $access_token;
                $data['name'] = $user->getName();

                return $this->bind($data);
            }
        }

        return;
    }

    /**
     * 三方用户注册/绑定账号（未登录时）.
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bind(array $data = [])
    {

        return view('pcview::socialite.bind', $data, $this->PlusData);
    }
}