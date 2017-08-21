<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class RankController extends BaseController
{
    public function index(Request $request)
    {
        $data = createRequest('GET', $url);

        return view('pcview::rank.index', $data, $this->PlusData);
    }

    public function _getRankList($type, $fids)
    {
    }

}
