<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class OauthController extends BaseController
{
    /**
     * 三方获取信息跳转
     * @param Request $request
     * @param $service
     * @return mixed
     */
    public function redirectToProvider(Request $request, $service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * 三方回调函数
     * @param Request $request
     * @param $service
     */
    public function handleProviderCallback(Request $request, $service)
    {
        $user = Socialite::driver($service)->user();
        dd($user);
    }


}