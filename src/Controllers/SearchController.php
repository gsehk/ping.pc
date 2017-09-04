<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class SearchController extends BaseController
{
    public function index(Request $request, int $type = 1, string $keywords = '')
    {
        $data['type'] = $type;
        $data['keywords'] = $keywords;

        return view('pcview::search.index', $data, $this->PlusData);
    }

    public function getData(Request $request) 
    {
        $type = $request->query('type');
        $limit = $request->query('limit') ?: 9;
        $after = $request->query('after') ?: 0;
        $offset = $request->query('offset') ?: 0;
        $keywords = $request->query('keywords') ?: '';

        switch ($type) {
            case '3':
                $params = [
                    'limit' => $limit,
                    'after' => $after,
                    'key' => $keywords
                ];

                $api =  '/api/v2/news';
                $datas = createRequest('GET', $api, $params);
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
                $api =  '/api/v2/user/search';
                $datas = createRequest('GET', $api, $params);
                $data['users'] = $datas;
                $html =  view('pcview::templates.user', $data, $this->PlusData)->render();

                break;
            
            default:
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