<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class RankController extends BaseController
{
    public function index(Request $request, int $mold = 1)
    {
        $data['mold'] = $mold;
        if ($mold == 1) {
            $data['follower'] = createRequest('GET', '/api/v2/ranks/followers');
            $data['balance'] = createRequest('GET', '/api/v2/ranks/balance');
            $data['income'] = createRequest('GET', '/api/v2/ranks/income');
            $data['check'] = createRequest('GET', 'api/v2/checkin-ranks');
            $data['experts'] = createRequest('GET', 'api/v2/question-ranks/experts');
            $data['likes'] = createRequest('GET', 'api/v2/question-ranks/likes');

        } elseif ($mold == 2) {
            $data['answers_day'] = createRequest('GET', 'api/v2/question-ranks/answers');
            $data['answers_week'] = createRequest('GET', 'api/v2/question-ranks/answers', ['type' => 'week']);
            $data['answers_month'] = createRequest('GET', 'api/v2/question-ranks/answers', ['type' => 'month']);

        } elseif ($mold == 3) {
            $data['feeds_day'] = createRequest('GET', '/api/v2/feeds/ranks');
            $data['feeds_week'] = createRequest('GET', '/api/v2/feeds/ranks', ['type' => 'week']);
            $data['feeds_month'] = createRequest('GET', '/api/v2/feeds/ranks', ['type' => 'month']);

        } elseif ($mold == 4) {
            $data['news_day'] = createRequest('GET', '/api/v2/news/ranks');
            $data['news_week'] = createRequest('GET', '/api/v2/news/ranks', ['type' => 'week']);
            $data['news_month'] = createRequest('GET', '/api/v2/news/ranks', ['type' => 'month']);
        }

        return view('pcview::rank.index', $data, $this->PlusData);
    }

    public function _getRankList(Request $request)
    {
        $genre = $request->input('genre') ?: '';
        $offset = $request->input('offset') ?: 0;
        $limit = $request->input('limit') ?: 0;
        switch ($genre) {
            case 'follower':
                $tabName = '粉丝数';
                $data = createRequest('GET', '/api/v2/ranks/followers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'balance':
                $tabName = '';
                $data = createRequest('GET', '/api/v2/ranks/balance', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'income':
                $tabName = '';
                $data = createRequest('GET', '/api/v2/ranks/income', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'check':
                $tabName = '';
                $data = createRequest('GET', '/api/v2/checkin-ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'experts':
                $tabName = '';
                $data = createRequest('GET', '/api/v2/question-ranks/experts', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'likes':
                $tabName = '问答点赞量';
                $data = createRequest('GET', '/api/v2/question-ranks/likes', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_day':
                $tabName = '问答量';
                $data = createRequest('GET', '/api/v2/question-ranks/answers', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_week':
                $tabName = '问答量';
                $data = createRequest('GET', '/api/v2/question-ranks/answers', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'answers_month':
                $tabName = '问答量';
                $data = createRequest('GET', '/api/v2/question-ranks/answers', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_day':
                $tabName = '点赞量';
                $data = createRequest('GET', '/api/v2/feeds/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_week':
                $tabName = '点赞量';
                $data = createRequest('GET', '/api/v2/feeds/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'feeds_month':
                $tabName = '点赞量';
                $data = createRequest('GET', '/api/v2/feeds/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_day':
                $tabName = '浏览量';
                $data = createRequest('GET', '/api/v2/news/ranks', ['offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_week':
                $tabName = '浏览量';
                $data = createRequest('GET', '/api/v2/news/ranks', ['type' => 'week', 'offset' => $offset, 'limit' => $limit]);
                break;
            case 'news_month':
                $tabName = '浏览量';
                $data = createRequest('GET', '/api/v2/news/ranks', ['type' => 'month', 'offset' => $offset, 'limit' => $limit]);
                break;
        }

        $return['count'] = count($data);
        $return['nowPage'] = $offset / $limit + 1;
        $return['html'] = view('pcview::rank.lists', [
            'post' => $data,
            'genre' => $genre,
            'tabName' => $tabName
        ])
            ->render();

        return response()->json([
            'status'  => true,
            'data' => $return,
        ]);
    }

}
