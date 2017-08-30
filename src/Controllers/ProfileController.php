<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class ProfileController extends BaseController
{
    public function index(Request $request, int $user_id = 0)
    {
        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();

        return view('pcview::profile.index', $data, $this->PlusData);
    }

    public function feeds(Request $request)
    {
        if ($request->cate == 'all') {
            $feeds = createRequest('GET', '/api/v2/feeds');
            $feed = clone $feeds['feeds'];
        }
        if ($request->cate == 'img') {
            # code...
        }
        $after = $feed->pop()->id ?? 0;
        $html = view('pcview::templates.profile_feed', $feeds, $this->PlusData)->render();

        return response()->json(static::createJsonData([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]));
    }

    public function news(Request $request)
    {
        $uid = $this->PlusData['TS']['id'] ?? 0;
        $limit = $request->query('limit', 20);
        $after = $request->query('after');
        $state = $request->query('type');
        $user = $request->query('user');
        $news = News::where('audit_status', $state)
                ->where('author', $user)
                ->when($after, function ($query) use ($after) {
                    return $query->where('id', '<', $after);
                })
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        $news->map(function($news) use ($uid) {
            $news->comments = $news->comments;
            $news->collect_count = $news->collection->count();
            $news->has_collect = $uid ? $news->collection->where('user_id', $uid)->where('news_id', $news->id)->count() : 0;
        });
        $new = clone $news;
        $datas['data'] = $news;
        $after = $new->pop()->id ?? 0;
        $html = view('pcview::templates.profile-news', $datas, $this->PlusData)->render();

        return response()->json(static::createJsonData([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]));
    }

}
