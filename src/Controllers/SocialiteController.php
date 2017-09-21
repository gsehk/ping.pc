<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;
use Overtrue\Socialite\SocialiteManager;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SocialiteController extends BaseController
{
    protected $config = [];

    public function __construct()
    {
        parent::__construct();
        $this->config = [
            'weibo' => [
                'weibo' => [
                    'client_id'     => '845138498',
                    'client_secret' => 'a75c65670ea302940b69edbcc1fedb81',
                    'redirect'      => env('APP_URL').'/socialite/weibo/callback',
                ]
            ],
            'qq' => [
               'qq' => [
                   'client_id'     => '101418557',
                   'client_secret' => '67d647556551c8e2c53f4ba315f87c93',
                   'redirect'      => env('APP_URL').'/socialite/qq/callback',
               ]
            ],
            'wechat' => [
               'wechat' => [
                   'client_id'     => '',
                   'client_secret' => '',
                   'redirect'      => env('APP_URL').'/socialite/wechat/callback',
               ]
            ],
        ];
    }

    /**
     * 三方登录
     * @param Request $request
     * @param $service
     */
    public function redirectToProvider(Request $request, $service)
    {

        $config = $this->config[$service] ?: [];

        if (!$config) {

            dd('暂未开通'.$service.'登录');
        }

        $socialite = new SocialiteManager($config);

        $response = $socialite->driver($service)->redirect();

        $response->send();
    }

    /**
     * 三方绑定
     * @param Request $request
     * @param $service
     * @author zuo
     */
    public function redirectToProviderByBind(Request $request, $service)
    {
        $config = $this->config[$service] ?: [];
        $config[$service]['redirect'] = $config[$service]['redirect'].'/bind';

        if (!$config) {

            dd('暂未开通'.$service.'登录');
        }

        $socialite = new SocialiteManager($config);

        $response = $socialite->driver($service)->with(['type' => 'bind'])->redirect();

        $response->send();
    }

    /**
     * 第三方回调页.
     * @param Request $request
     * @param $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|void
     */
    public function handleProviderCallback(Request $request, $service)
    {
        $config = $this->config[$service] ?: [];

        $socialite = new SocialiteManager($config);
        $user = $socialite->driver($service)->user();

        $access_token = $user->getToken()->access_token;

        $res = createRequest('POST', '/api/v2/socialite/'.$service, ['access_token' => $access_token]);

        if (isset($res['token'])) { // 登录

            return redirect(route('pc:token', ['token' => $res['token'], 'type' => 0]));

        } elseif (isset($res['message']) && $res['message'] == '请绑定账号') { // 绑定、注册

            $data['other_type'] = $service;
            $data['access_token'] = $access_token;
            $data['name'] = $user->getName();

            return $this->bind($data);
        }

        return;
    }

    public function handleProviderByBindCallback(Request $request, $service)
    {
        $config = $this->config[$service] ?: [];

        $socialite = new SocialiteManager($config);
        $user = $socialite->driver($service)->user();
        $access_token = $user->getToken()->access_token;

        $res = createRequest('PATCH', '/api/v2/user/socialite/'.$service, ['access_token' => $access_token]);

        if (isset($res['message']) && $res['message'] = '你已绑定了其他第三方账号') {
            dd($res['message']);
        }

        return  redirect(route('pc:success', ['message' => '绑定成功', 'content' => '您的账号已成功绑定！', 'url' => Route('pc:binds')]));
    }

    /**
     * 三方用户注册/绑定账号.
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bind(array $data = [])
    {

        return view('pcview::socialite.bind', $data, $this->PlusData);
    }
}