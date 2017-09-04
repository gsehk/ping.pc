<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SearchController extends BaseController
{
    public function index(Request $request, string $keywords = '', int $type = 1)
    {
        $data['type'] = $type;
        $data['keywords'] = $keywords;

        switch ($type) {
            case 1: // 动态
                
                break;
            case 2: // 问答
                
                break;
            case 3: // 文章
                
                break;
            case 4: // 用户
                
                break;
            case 5: // 圈子
                
                break;
        }


        return view('pcview::search.index', $data, $this->PlusData);
    }
}