<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class PostController extends BaseController
{

    /**
     * 获取帖子打赏列表.
     *
     * @author 28youth
     * @param  Request $request
     * @param  int     $post_id 帖子id
     * @return mixed
     */
    public function rewards(Request $request, int $post_id)
    {
        $params = [
            'limit' => $request->query('limit', 15),
            'offset' => $request->query('offset', 0),
            'order' => $request->query('order', 'desc'),
            'order_type' => $request->query('order_type', 'date'),
        ];

        $data['rewards'] = createRequest('GET', '/api/v2/plus-group/group-posts/'.$post_id.'/rewards', $params);
        $data['app'] = '帖子';

        return view('pcview::templates.rewards', $data, $this->PlusData)->render();
    }
}