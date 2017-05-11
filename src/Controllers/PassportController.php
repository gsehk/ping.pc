<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Gregwar\Captcha\CaptchaBuilder;
use Zhiyi\Plus\Models\VerifyCode;
use Zhiyi\Plus\Models\User;
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
     * [login 登录页]
     * @Author Foreach<hhhcode@outlook.com>
     * @return [type] [description]
     */
    public function index()
    {

        $users = DB::table('users')->simplePaginate(15);
        return view('passport.login', ['users' => $users]);

        
        if ($this->guard()->check()) {
            return redirect(route('pc:feed'));
        }

    	return view('passport.login');
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

    public function doRegister(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $password = $request->input('password', '');

        // 图形验证码判断
        $captcha = $request->input('captcha');

        if (Session::get('milkcaptcha') != $captcha) {
            return response()->json(static::createJsonData([
                'code' => 91002,
            ]))->setStatusCode(403);
        }

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

        return view('passport.perfect');
    }

    public function findPassword(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('passport.findpwd', ['type' => $type]);
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
    public function checkCaptcha()
    {
        $userInput = \Request::get('captcha');

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
