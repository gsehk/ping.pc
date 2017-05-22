<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Gregwar\Captcha\CaptchaBuilder;
use Zhuzhichao\IpLocationZh\Ip;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\AuthToken;
use Zhiyi\Plus\Models\LoginRecord;
use Session;

class PassportController extends BaseController
{
    use AuthenticatesUsers {
        login as traitLogin;
    }

    /**
     * [username 登录字段]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * [redirectTo 登录成功跳转]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function redirectTo()
    {
        return route('pc:feed');
    }

    /**
     * [login 登录页]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function index()
    {
        if ($this->guard()->check()) {
            return redirect(route('pc:feed'));
        }

    	return view('pcview::passport.login', [], $this->mergeData);
    }

    /**
     * [doLogin 登录]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function doLogin(Request $request)
    {
        $this->guard()->logout();
        $request->session()->regenerate();

        return $this->traitLogin($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // token
        $deviceCode = '';
        $token = new AuthToken();
        $token->token = md5($deviceCode.str_random(32));
        $token->refresh_token = md5($deviceCode.str_random(32));
        $token->user_id = $user->id;
        $token->expires = 0;
        $token->state = 1;

        // 登录记录
        $clientIp = $request->getClientIp();
        $loginrecord = new LoginRecord();
        $loginrecord->ip = $clientIp;

        $location = (array)Ip::find($clientIp);
        array_filter($location);
        $loginrecord->address = trim(implode(' ', $location));
        $loginrecord->device_system = '';
        $loginrecord->device_name = '';
        $loginrecord->device_model = '';
        $loginrecord->device_code = $deviceCode;

        DB::transaction(function () use ($token, $user, $loginrecord) {
            $user->tokens()->update(['state' => 0]);
            $user->tokens()->delete();
            $token->save();
            $user->loginRecords()->save($loginrecord);
        });

        $history = Session::pull('history') ?: '';

        return response()->json(static::createJsonData([
            'status'  => true,
            'data' => $history
        ]));
    }



    /**
     * [logout 登出]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect(route('pc:index'));
    }

    /**
     * [register 注册页]
     * @Author Foreach<hhhcode@outlook.com>
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function register(Request $request)
    {
        return view('pcview::passport.register', $this->mergeData);
    }

    public function doRegister(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $password = $request->input('password', '');

        // 注册用户
        $user = new User();
        $user->name = $name;
        $user->phone = $phone;
        $user->createPassword($password);
        $user->save();

        return $this->doLogin($request);
    }

    public function perfect()
    {

        return view('pcview::passport.perfect');
    }

    /**
     * [findPassword 找回密码]
     * @Author Foreach<hhhcode@outlook.com>
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function findPassword(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('pcview::passport.findpwd', ['type' => $type], $this->mergeData);
    }

    /**
     * [findPassword 找回密码]
     * @Author Foreach<hhhcode@outlook.com>
     * @param  Request $request [description]
     * @return [type] [description]
     */
    public function doFindpwd(Request $request)
    {
        $password = $request->input('password', '');
        $user = $request->attributes->get('user');
        $user->createPassword($password);
        $user->save();

        return response()->json([
            'status'  => true,
            'code'    => 0,
            'message' => '重置密码成功',
            'data'    => null,
        ]);
    }

    /**
     * [captcha 获取验证码]
     * @Author Foreach<hhhcode@outlook.com>
     * @param  [type] $tmp [description]
     * @return [type] [description]
     */
    public function captcha($tmp)
    {
        // 生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        // 设置背景
        $builder->setBackgroundColor(237,237,237);
        // 设置字体大小
        $builder->setBackgroundColor(237,237,237);
        // 可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        // 获取验证码的内容
        $phrase = $builder->getPhrase();

        // 把内容存入session
        Session::flash('milkcaptcha', $phrase);
        // 生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    /**
     * [checkCaptcha 验证验证码]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function checkCaptcha(Request $request)
    {
        $userInput = $request->input('captcha');

        if (Session::get('milkcaptcha') == $userInput) {
            return response()->json(static::createJsonData([
            'status'  => true,
            ]));
        } else {
            return response()->json(static::createJsonData([
            'status'  => false,
            ]));
        }
    }
}
