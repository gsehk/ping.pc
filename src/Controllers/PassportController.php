<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Cookie;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class PassportController extends BaseController
{
    /**
     * 凭据存储
     * @author Foreach
     * @param  string $token [凭据]
     * @param  int    $type  [跳转类型]
     * @return mixed
     */
    public function token(string $token, int $type)
    {
        Session::put('token', $token);
        Session::put('initial_password', true);

        // 若设置history
        if (Session::get('history')) {
            $history = Session::get('history');
            Session::forget('history');
            return redirect($history);
        }

        // 若设置referer_url
        if (isset($_COOKIE['referer_url']) && $_COOKIE['referer_url'] != '') {
            return redirect($_COOKIE['referer_url']);
        }

        if ($type) {
            return redirect(route('pc:perfect'));
        } else {
            return redirect(route('pc:feeds'));
        }
    }

    /**
     * 登录
     * @author Foreach
     * @return mixed
     */
    public function index()
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

    	return view('pcview::passport.login', [], $this->PlusData);
    }

    /**
     * 登出
     * @author Foreach
     * @return mixed
     */
    public function logout()
    {
        Session::flush();
        return redirect(route('pc:feeds'));
    }

    /**
     * 注册
     * @author Foreach
     * @param  int $type [注册类型]
     * @return mixed
     */
    public function register(int $type = 0)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }

        return view('pcview::passport.register', ['type' => $type], $this->PlusData);
    }

    /**
     * 找回密码
     * @author Foreach
     * @param  int $type [找回类型]
     * @return mixed
     */
    public function findPassword(int $type = 0)
    {
        if ($this->PlusData['TS'] != null) {
            return redirect(route('pc:feeds'));
        }
        
        return view('pcview::passport.findpwd', ['type' => $type], $this->PlusData);
    }

    /**
     * 完善资料
     * @author Foreach
     * @return mixed
     */
    public function perfect()
    {
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['user_tag'] = createRequest('GET', '/api/v2/user/tags');
        return view('pcview::passport.perfect', $data, $this->PlusData);
    }

    /**
     * 图形验证码生成
     * @author Foreach
     * @return mixed
     */
    public function captcha()
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
     * 图形验证码验证
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCaptcha(Request $request)
    {
        $input = $request->input('captcha');

        if (Session::get('milkcaptcha') == $input) {
            return response()->json([], 200);
        } else {
            return response()->json([], 501);
        }
    }
}
