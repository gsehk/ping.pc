<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SearchController extends BaseController
{
    /**
     * 搜索
     * @author Foreach
     * @param  Request     $request
     * @param  int|integer $type     [搜索类型]
     * @param  string      $keywords [关键字]
     * @return mixed
     */
    public function index(Request $request, int $type = 1, string $keywords = '')
    {
        $data['type'] = $type;
        $data['keywords'] = $keywords;

        return view('pcview::search.index', $data, $this->PlusData);
    }

    /**
     * 搜索获取数据
     * @author Foreach
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $type = $request->query('type');
        $limit = $request->query('limit') ?: 9;
        $after = $request->query('after') ?: 0;
        $offset = $request->query('offset') ?: 0;
        $keywords = $request->query('keywords') ?: '';

        switch ($type) {
            case '1':
                $params = [
                    'limit' => $limit,
                    'type' => 'new',
                    'search' => $keywords,
                    'after' => $after
                ];

                $datas = createRequest('GET', '/api/v2/feeds', $params);
                $data = $datas;
                $feed = clone $data['feeds'];
                $after = $feed->pop()->id ?? 0;

                $data['conw'] = 735;
                $data['conh'] = 545;
                $html = view('pcview::templates.feeds', $data, $this->PlusData)->render();
                break;
            case '2':
                $params = [
                    'type' => 'all',
                    'limit' => $limit,
                    'offset' => $offset,
                    'subject' => $keywords
                ];

                $datas = createRequest('GET', '/api/v2/questions', $params);
                $data['data'] = $datas;
                $question = clone $data['data'];
                $after = $question->pop()->id ?? 0;
                $html = view('pcview::templates.question', $data, $this->PlusData)->render();
                break;
            case '3':
                $params = [
                    'limit' => $limit,
                    'after' => $after,
                    'key' => $keywords
                ];

                $datas = createRequest('GET', '/api/v2/news', $params);
                $data['news'] = $datas;
                $new = clone $data['news'];
                $after = $new->pop()->id ?? 0;
                $html = view('pcview::templates.news', $data, $this->PlusData)->render();

                break;
            case '4':
                $params = [
                    'limit' => $limit,
                    'offset' => $offset,
                    'keyword' => $keywords
                ];

                $datas = createRequest('GET', '/api/v2/user/search', $params);
                $data['users'] = $datas;
                $html =  view('pcview::templates.user', $data, $this->PlusData)->render();
                break;
            case '5':
                $user = $this->PlusData['TS']['id'] ?? 0;
                $params = [
                    'after' => $after,
                    'search' => $keywords
                ];

                $datas = createRequest('GET', '/api/v2/groups', $params);

                $data['group'] = $datas;
                $group = clone $data['group'];
                $after = $group->pop()->id ?? 0;
                $html = view('pcview::templates.group', $data, $this->PlusData)->render();
                break;

        }

        return response()->json([
            'status'  => true,
            'data' => $html,
            'count' => count($datas),
            'after' => $after
        ]);
    }
}