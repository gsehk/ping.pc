<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Gregwar\Captcha\CaptchaBuilder;
use Session;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

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
     * [index 登录页]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function index()
    {
        if ($this->guard()->check()) {
            return redirect(route('pc:feed'));
        }

    	return view('passport.login');
    }

    /**
     * [login 登录]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function login(Request $request)
    {
        $this->guard()->logout();
        $request->session()->regenerate();

        return $this->traitLogin($request);
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
        return view('passport.register');
    }

    public function perfect()
    {

        return view('passport.perfect');
    }

    public function findPassword(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('passport.findpwd', ['type' => $type]);
    }

    public function captcha($tmp)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::flash('milkcaptcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}
