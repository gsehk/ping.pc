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
    public function wallet()
    {
        $data['order'] = createRequest('GET', '/api/v2/wallet/charges');

        return view('pcview::account.wallet', $data, $this->PlusData);
    }

    /**
     * 提现记录.
     *
     * @return mixed
     */
    public function withdraw(Request $request)
    {
        $data['type'] = 2;

        return view('pcview::account.withdraw', $data, $this->PlusData);
    }

    /**
     * 交易明细.
     *
     * @return mixed
     */
    public function trades(Request $request, int $type = 2)
    {
        $data['type'] = $type;

        return view('pcview::account.trades', $data, $this->PlusData);
    }

    /**
     * 钱包规则.
     *
     * @return mixed
     */
    public function withrule()
    {
        $data['wallet'] = createRequest('GET', '/api/v2/wallet');

        return view('pcview::account.withrule', $data, $this->PlusData);
    }

    /**
     * 订单记录.
     *
     * @param  Illuminate\Http\Request $request
     * @return mixed
     */
    public function order(Request $request, int $order_id = 0)
    {
        $type = $request->query('type') ?? '';
        $cate = $request->query('cate') ?? 1;

        // 交易记录详情
        if ($order_id) {
            $order = createRequest('GET', '/api/v2/wallet/charges/'.$order_id);
            if ($order->channel == 'user') {
                $order->user = $order->user;
            }
            $data['order'] = $order;

            return view('pcview::account.detail', $data, $this->PlusData);
        }

        // 交易记录列表
        $params = [
            'after' => $request->query('after') ?: 0
        ];
        if ($type == 0 || $type == 1) {
            $params['action'] = $type;
        }
        $orders = createRequest('GET', '/api/v2/wallet/charges', $params);

        // 提现记录列表
        if ($cate == 2) {
            $orders = createRequest('GET', '/api/v2/wallet/cashes', $params);
        }
        $order = clone $orders;
        $after = $order->pop()->id ?? 0;
        $data['order'] = $orders;
        $data['type'] = $cate;

        $html = view('pcview::templates.order', $data)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after
        ]);
    }
}