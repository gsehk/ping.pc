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
     * 交易记录.
     *
     * @param  Illuminate\Http\Request $request
     * @return mixed
     */
    public function order(Request $request, int $order_id = 0)
    {
        if ($order_id) {
            $order = createRequest('GET', '/api/v2/wallet/charges/'.$order_id);
            if ($order->channel == 'user') {
                $order->user = $order->user;
            }
            $data['order'] = $order;

            return view('pcview::account.detail', $data, $this->PlusData);
        }

        $params = [
            'after' => $request->query('after') ?: 0
        ];
        $orders = createRequest('GET', '/api/v2/wallet/charges', $params);

        $order = clone $orders;
        $after = $order->pop()->id ?? 0;
        $data['order'] = $orders;

        $html = view('pcview::templates.order', $data)->render();

        return response()->json([
            'status'  => true,
            'data' => $html,
            'after' => $after
        ]);
    }

}