<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;
use Overtrue\Socialite\SocialiteManager;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SocialiteController extends BaseController
{
    public function redirectToProvider(Request $request, $service)
    {
        switch ($service) {
            case 'weibo':
                $config = [
                    'weibo' => [
                        'client_id'     => '2145185973',
                        'client_secret' => '3e7a5ccd8cc36cadcd06e2eb6239230d',
                        'redirect'      => $this->PlusData['routes']['siteurl'].'/socialite/'.$service.'/callback',
                    ],
                ];

                break;

            default:
                break;
        }


        $socialite = new SocialiteManager($config);

        $response = $socialite->driver($service)->redirect();

        $response->send();
    }

    public function handleProviderCallback(Request $request, $service)
    {
        switch ($service) {
            case 'weibo':
                $config = [
                    'weibo' => [
                        'client_id'     => '2145185973',
                        'client_secret' => '3e7a5ccd8cc36cadcd06e2eb6239230d',
                        'redirect'      => $this->PlusData['routes']['siteurl'].'/socialite/'.$service.'/callback',
                    ],
                ];

                break;

            default:
                break;
        }

        $socialite = new SocialiteManager($config);

        $user = $socialite->driver($service)->user();

        $access_token = $user->getToken()->access_token;
        $res = createRequest('post', '/api/v2/socialite/'.$service, ['access_token' => $access_token]);
        dd($res);

        //$res['message'] = '请绑定账号';
        if ($res['message'] == '请绑定账号') {
            $data['other_type'] = $service;
            $data['access_token'] = $access_token;
            $data['name'] = '你';

           return $this->bind($data);
        } else {    // 登录
            createRequest('GET', '/passport/token/'.$res['token'].'/0');
        }

        return;
    }

    public function bind(array $data = [])
    {
        return view('pcview::socialite.bind', $data, $this->PlusData);
    }
}