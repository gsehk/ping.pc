<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class FeedController extends BaseController
{
    public function index(Request $request)
    {
        $data['type'] = $request->input('type') ?: ($this->PlusData['TS'] ? 'follow' : 'hot');

        $this->PlusData['current'] = 'feeds';
        return view('pcview::feed.index', $data, $this->PlusData);
    }

    public function list(Request $request)
    {
        $params = [
            'type' => $request->query('type'),
            'after' => $request->query('after') ?: 0
        ];
        $feeds = createRequest('GET', '/api/v2/feeds', $params);
        $feed = clone $feeds['feeds'];
        $after = $feed->pop()->id ?? 0;
        $feedData = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $feedData,
                'after' => $after
        ]);
    }

    public function feed(Request $request)
    {
        $feeds['feeds'] = collect();
        $feed = createRequest('GET', '/api/v2/feeds/'.$request->feed_id);
        $feeds['feeds']->push($feed);
        $feedData = view('pcview::templates.feeds', $feeds, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $feedData
        ]);
    }

    public function read(Request $request, int $feed_id)
    {
        $feed = createRequest('GET', '/api/v2/feeds/'.$feed_id);
        $feed->collect_count = $feed->collection->count();
        $data['feed'] = $feed;
        $data['user'] = $feed->user;
        $data['user']['followers'] = $feed->user->followers()->count();
        $data['user']['followings'] = $feed->user->followings()->count();

        return view('pcview::feed.read', $data, $this->PlusData);
    }
}
