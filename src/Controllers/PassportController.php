<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gregwar\Captcha\CaptchaBuilder;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\User;

use function Zhiyi\Plus\username;

class PassportController extends BaseController
{

    public function token(Request $request, int $mid, string $token)
    {
        Session::put('mid', $mid);
        Session::put('token', $token);
        return redirect(route('pc:feed'));
    }

    public function index(Request $request)
    {
        if ($this->mergeData['TS'] != null) {
            return redirect(route('pc:feed'));
        }

    	return view('pcview::passport.login', [], $this->mergeData);
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect(route('pc:index'));
    }

    public function perfect()
    {
        return view('pcview::passport.perfect');
    }

    public function register()
    {
        return view('pcview::passport.register', [], $this->mergeData);
    }

    public function findPassword(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('pcview::passport.findpwd', ['type' => $type], $this->mergeData);
    }

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
            return response()->json([], 200);
        } else {
            return response()->json([], 501);
        }
    }
}
