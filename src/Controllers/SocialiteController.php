<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use SlimKit\PlusSocialite\SocialiteManager;
use Overtrue\Socialite\SocialiteManager as Socialite;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SocialiteController extends BaseController
{

    protected $socialite;

    /**
     * Provider maps.
     *
     * @var array
     */
    protected $providerMap = [
        'qq' => 'QQ',
        'weibo' => 'Weibo',
        'wechat' => 'WeChat',
    ];

    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }


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

        $res = $this->provider($service)->authUser($accessToken);

        dd($res);
    }

    public function oauthUser(int $type = 0)
    {
        $data['type'] = $type;
        $data['other_type'] = 'qq';
        $data['access_token'] = '1d4c8241f072a0e8a690948fc6342ef7';
        $data['name'] = '你好';

        return view('pcview::socialite.bind', $data, $this->PlusData);
    }


    protected function provider(string $provider): Sociable
    {
        return $this->socialite->driver(
            $this->getProviderName($provider)
        );
    }
}