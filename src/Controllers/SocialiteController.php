<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;
use Overtrue\Socialite\SocialiteManager;
use Illuminate\Http\Request;

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

        $user->getId();        // 1472352
        $user->getNickname();  // "overtrue"
        $user->getName();      // "安正超"
        $user->getEmail();     // "anzhengchao@gmail.com"
        $user->getProviderName(); // GitHub
        dd($user);
    }

    public function oauthUser(int $type = 0)
    {
        $data['type'] = $type;
        $data['other_type'] = 'qq';
        $data['access_token'] = '1d4c8241f072a0e8a690948fc6342ef7';
        $data['name'] = '你好';

        return view('pcview::socialite.bind', $data, $this->PlusData);
    }
}