<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Session;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class AccountController extends BaseController
{

    /**
     * 基本设置
     * @author Foreach
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->PlusData['account_cur'] = 'index';

        $user_id = $this->PlusData['TS']->id ?? 0;

        $user = $this->PlusData['TS'];
        $user['city'] = explode(' ', $user['location']);
        $data['user'] = $user;
        return view('pcview::account.index', $data, $this->PlusData);
    }

    /**
     * 认证
     * @author 28youth
     * @return mixed
     */
    public function authenticate()
    {
        $this->PlusData['account_cur'] = 'authenticate';

        $templet = 'authenticate';
        $data['info'] = createRequest('GET', '/api/v2/user/certification');
        if (isset($data['info']['status'])) {
            $templet = 'authinfo';
        }
        return view('pcview::account.'.$templet, $data, $this->PlusData);
    }

    /**
     * 更新认证
     * @author 28youth
     * @return mixed
     */
    public function updateAuthenticate()
    {
        $this->PlusData['account_cur'] = 'authenticate';
        $data['info'] = createRequest('GET', '/api/v2/user/certification');
        if ($data['info']['status'] == 1) {
            return redirect('/account/authenticate');
        }

        return view('pcview::account.update_authenticate', $data, $this->PlusData);
    }

    /**
     * 标签管理
     * @author 28youth
     * @return mixed
     */
    public function tags()
    {
        $this->PlusData['account_cur'] = 'tags';

        $user_id = $this->PlusData['TS']->id ?? 0;
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['user_tag'] = createRequest('GET', '/api/v2/user/tags');
        return view('pcview::account.tags', $data, $this->PlusData);
    }

    /**
     * 密码修改
     * @author 28youth
     * @return mixed
     */
    public function security()
    {
        $this->PlusData['account_cur'] = 'security';

        $showPassword = Session::get('initial_password');
        $data['showPassword'] = $showPassword;

        return view('pcview::account.security', $data, $this->PlusData);
    }

    /**
     * 我的钱包
     * @author Foreach
     * @param  Request     $request 
     * @param  int|integer $type    [类型]
     * @return mixed
     */
    public function wallet(Request $request, int $type = 1)
    {
        $this->PlusData['account_cur'] = 'wallet';

        $data['order'] = createRequest('GET', '/api/v2/wallet/charges');
        $data['wallet'] = createRequest('GET', '/api/v2/wallet');
        $data['type'] = $type;

        return view('pcview::account.wallet', $data, $this->PlusData);
    }

    /**
     * 钱包记录列表
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function records(Request $request)
    {
        $type = $request->query('type');

        $params = [
            'after' => $request->query('after') ?: 0
        ];
        // 交易记录列表
        if ($type == 2) {
            $cate = $request->query('cate');
            if ($cate == 2) $params['action'] = 1;
            if ($cate == 3) $params['action'] = 0;
            $records = createRequest('GET', '/api/v2/wallet/charges', $params);
        }

        // 提现记录列表
        if ($type == 3) {
            $records = createRequest('GET', '/api/v2/wallet/cashes', $params);
        }
        $record = clone $records;
        $after = $record->pop()->id ?? 0;
        $data['records'] = $records;
        $data['type'] = $type;
        $data['loadcount'] = $request->query('loadcount');

        $html = view('pcview::account.walletrecords', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after
        ]);
    }

    /**
     * 钱包记录详情
     * @author Foreach
     * @param  Request $request   
     * @param  int     $record_id [记录id]
     * @return mixed
     */
    public function record(Request $request, int $record_id)
    {
        $order = createRequest('GET', '/api/v2/wallet/charges/'.$record_id);
        if ($order->channel == 'user') {
            $order->user = $order->user;
        }
        $data['order'] = $order;

        return view('pcview::account.detail', $data, $this->PlusData);
    }

    /**
     * 充值
     * @author Foreach
     * @return mixed
     */
    public function pay()
    {
        $this->PlusData['account_cur'] = 'wallet';

        return view('pcview::account.walletpay', $this->PlusData);
    }

    /**
     * ping++充值调起
     * @author Foreach
     * @param  Request $request
     * @return mixed
     */
    public function gateway(Request $request)
    {
        $data['charge'] = $request->query('res');

        return view('pcview::account.gateway', $data, $this->PlusData);
    }

    /**
     * 提现
     * @author Foreach
     * @return mixed
     */
    public function draw()
    {
        $this->PlusData['account_cur'] = 'wallet';

        return view('pcview::account.walletdraw', $this->PlusData);
    }

    /**
     * 获取绑定信息
     * @author ZsyD
     * @return mixed
     */
    public function getMyBinds()
    {
        $this->PlusData['account_cur'] = 'binds';

        $data = [
            'phone' => false,
            'email' => false,
            'qq' => false,
            'wechat' => false,
            'weibo' => false
        ];
        // 手机邮箱绑定状态
        $user = createRequest('GET', '/api/v2/user');

        $data['phone'] = (boolean)$user->phone;
        $data['email'] = (boolean)$user->email;

        // 三方绑定状态
        $binds = createRequest('GET', '/api/v2/user/socialite');
        foreach ($binds as $v) {
            $data[$v] = true;
        }

        return view('pcview::account.binds', $data, $this->PlusData);
    }
}