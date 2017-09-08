<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class AccountController extends BaseController
{

    /**
     * 基本资料.
     *
     * @param  Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $user_id = $this->PlusData['TS']->id ?? 0;

        $user = $this->PlusData['TS'];
        $user['city'] = explode(' ', $user['location']);
        $data['user'] = $user;
        return view('pcview::account.index', $data, $this->PlusData);
    }

    /**
     * 认证管理.
     *
     * @return mixed
     */
    public function authenticate()
    {
        $templet = 'authenticate';
        $data['info'] = createRequest('GET', '/api/v2/user/certification');
        if (isset($data['info']['status'])) {
            $templet = 'authinfo';
        }
        return view('pcview::account.'.$templet, $data, $this->PlusData);
    }

    /**
     * 标签管理.
     *
     * @return mixed
     */
    public function tags()
    {
        $user_id = $this->PlusData['TS']->id ?? 0;
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['user_tag'] = createRequest('GET', '/api/v2/user/tags');
        return view('pcview::account.tags', $data, $this->PlusData);
    }

    /**
     * 安全设置.
     *
     * @return mixed
     */
    public function security()
    {
        return view('pcview::account.security', $this->PlusData);
    }

    /**
     * 我的钱包.
     *
     * @return mixed
     */
    public function wallet(Request $request, int $type = 1)
    {
        $data['order'] = createRequest('GET', '/api/v2/wallet/charges');
        $data['wallet'] = createRequest('GET', '/api/v2/wallet');
        $data['type'] = $type;

        return view('pcview::account.wallet', $data, $this->PlusData);
    }

    /**
     * 订单记录.
     *
     * @param  Illuminate\Http\Request $request
     * @return mixed
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

        $html = view('pcview::account.walletrecords', $data)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after
        ]);
    }

    public function record(Request $request, int $record_id)
    {
        $order = createRequest('GET', '/api/v2/wallet/charges/'.$record_id);
        if ($order->channel == 'user') {
            $order->user = $order->user;
        }
        $data['order'] = $order;

        return view('pcview::account.detail', $data, $this->PlusData);
    }

    public function pay()
    {
        return view('pcview::account.pay', $this->PlusData);
    }

}