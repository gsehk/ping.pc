<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class ProfileController extends BaseController
{
    public function feeds(Request $request, int $user_id = 0)
    {
        if ($request->ajax()) {
            $params = [
                'type' => $request->query('type'),
                'user' => $request->query('user'),
                'after' => $request->query('after', 0),
            ];
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1: //全部
                    $feeds = createRequest('GET', '/api/v2/feeds', $params);
                    $feed = clone $feeds['feeds'];
                    $after = $feed->pop()->id ?? 0;
                    $html = view('pcview::templates.profile_feed', $feeds, $this->PlusData)->render();
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json(static::createJsonData([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]));
        }

        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();
        $data['user']->hasFollower = $data['user']->hasFollower($request->user());

        return view('pcview::profile.index', $data, $this->PlusData);
    }

    /**
     * 投稿文章.
     *
     * @param  Request $request [description]
     * @return mixed
     */
    public function news(Request $request)
    {
        if ($request->ajax()) {
            $params = [
                'type' => $request->query('type'),
                'after' => $request->query('after', 0)
            ];
            $news = createRequest('GET', '/api/v2/user/news/contributes', $params);
            $news->map(function($item){
                $item->collection_count = $item->collections->count();
            });
            $new = clone $news;
            $after = $new->pop()->id ?? 0;
            $data['data'] = $news;

            $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        $data['user'] = $this->PlusData['TS'];
        $data['type'] = 0;

        return view('pcview::profile.news', $data, $this->PlusData);
    }

    public function collect(Request $request)
    {
        if ($request->ajax()) {
            $params = [
                'after' => $request->query('after', 0)
            ];
            $cate = $request->query('cate', 1);
            switch ($cate) {
                case 1:
                    $feeds = createRequest('GET', '/api/v2/feeds/collections', $params);
                    $feed = clone $feeds;
                    $after = $feed->pop()->id ?? 0;
                    $data['feeds'] = $feeds;
                    $html = view('pcview::templates.collect_feeds', $data, $this->PlusData)->render();
                    break;
                case 2:
                    $news = createRequest('GET', '/api/v2/news/collections', $params);
                    $news->map(function($item){
                        $item->collection_count = $item->collections->count();
                        $item->comment_count = $item->comments->count();
                    });
                    $new = clone $news;
                    $after = $new->pop()->id ?? 0;
                    $data['data'] = $news;
                    $html = view('pcview::templates.profile_news', $data, $this->PlusData)->render();
                    break;
                default:
                    # code...
                    break;
            }

            return response()->json([
                'status' => true,
                'after' => $after,
                'data' => $html
            ]);
        }

        $data['user'] = $this->PlusData['TS'];
        $data['type'] = 0;

        return view('pcview::profile.collect', $data, $this->PlusData);
    }

}
