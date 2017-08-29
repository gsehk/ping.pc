<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class FeedController extends BaseController
{
    public function index(Request $request)
    {
        $data['type'] = $request->input('type') ?: ($this->PlusData['TS'] ? 'follow' : 'hot');
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
        $feeds['feed'] = createRequest('GET', '/api/v2/feeds/'.$request->feed);
        $feedData = view('pcview::templates.feed', $feeds, $this->PlusData)->render();

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

        // dd($data);
        return view('pcview::feed.read', $data, $this->PlusData);
    }
}
