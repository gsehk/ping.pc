<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class AccountController extends BaseController
{

    public function index(Request $request)
    {
        $user_id = $this->PlusData['TS']->id ?? 0;

        $user = $this->PlusData['TS'];
        $user['city'] = explode(' ', $user['location']);
        $data['user'] = $user;
        return view('pcview::account.index', $data, $this->PlusData);
    }

    public function authenticate()
    {
        return view('pcview::account.authenticate', $this->PlusData);
    }

    public function tags()
    {
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        // dd($data['tags']->toArray());
        return view('pcview::account.tags', $data, $this->PlusData);
    }
}