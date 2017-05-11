<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services\FeedCount;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
    	$data['type'] = $request->input('type') ?: 1;
        
    	return view('home.index', $data, $this->mergeData);
    }

    public function formatFeedList($feeds, $uid)
    {
        $datas = [];
        foreach ($feeds as $feed) {
            $data = [];
            $data['user_id'] = $feed->user_id;
            $data['feed_mark'] = $feed->feed_mark;
            // 动态数据
            $data['feed'] = [];
            $data['feed']['feed_id'] = $feed->id;
            $data['feed']['feed_title'] = $feed->feed_title ?? '';
            $data['feed']['feed_content'] = $feed->feed_content;
            $data['feed']['created_at'] = $feed->created_at->toDateTimeString();
            $data['feed']['feed_from'] = $feed->feed_from;
            $data['feed']['storages'] = $feed->storages->map(function ($storage) {
                return ['storage_id' => $storage->id, 'width' => $storage->image_width, 'height' => $storage->image_height];
            });
            // 工具数据
            $data['tool'] = [];
            $data['tool']['feed_view_count'] = $feed->feed_view_count;
            $data['tool']['feed_digg_count'] = $feed->feed_digg_count;
            $data['tool']['feed_comment_count'] = $feed->feed_comment_count;
            // 暂时剔除当前登录用户判定
            $data['tool']['is_digg_feed'] = $uid ? FeedDigg::byFeedId($feed->id)->byUserId($uid)->count() : 0;
            $data['tool']['is_collection_feed'] = $uid ? FeedCollection::where('feed_id', $feed->id)->where('user_id', $uid)->count() : 0;
            // 最新3条评论
            $data['comments'] = [];

            $getCommendsNumber = 5;
            $data['comments'] = $feed->comments()
                ->orderBy('id', 'desc')
                ->take($getCommendsNumber)
                ->select(['id', 'user_id', 'created_at', 'comment_content', 'reply_to_user_id', 'comment_mark'])
                ->get()
                ->toArray();
            $data['user'] = $feed->user()
                ->select('id', 'name')
                ->with('datas')
                ->first()->toArray();
            foreach ($data['user']['datas'] as $k => $v) {
                $data['user'][$v['profile']] = $v['pivot']['user_profile_setting_data'];
            }
            $datas[] = $data;
        }
        $feedList['data'] = $datas;
        $feedData['html'] = view('templet.feed', $feedList, $this->mergeData, true);
        $feedData['maxid'] = count($datas)>0 ? $datas[count($datas)-1]['feed']['feed_id'] : 0;

        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '动态列表获取成功',
                'data' => $feedData,
            ])->setStatusCode(200);
    }

    /**
     * 我关注的动态列表构建.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getFollowFeeds(Request $request)
    {
        $user_id = $this->mergeData['user']->id ?? 0;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $feeds = Feed::orderBy('id', 'DESC')
            ->whereIn('user_id', array_merge([$user_id], $request->user()->follows->pluck('following_user_id')->toArray()))
            ->where(function ($query) use ($request) {
                if ($request->max_id > 0) {
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->take($limit)
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }

   /**
     * 热门动态列表构建.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHotFeeds(Request $request)
    {
        $user_id = $this->mergeData['user']->id ?? 0;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $page = $request->page ?? 1;
        $skip = ($page - 1) * $limit;

        $feeds = Feed::orderBy('id', 'DESC')
            ->whereIn('id', FeedDigg::groupBy('feed_id')
                ->take($limit)
                ->select('feed_id', DB::raw('COUNT(user_id) as diggcount'))
                ->where('created_at', '>', Carbon::now()->subMonth()->toDateTimeString())
                ->orderBy('diggcount', 'desc')
                ->orderBy('feed_id', 'desc')
                ->skip($skip)
                ->pluck('feed_id')
                )
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }

    /**
     * 最新动态列表构建.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getNewFeeds(Request $request)
    {
        $user_id = $this->mergeData['user']->id ?? 0;
        $feed_ids = $request->input('feed_ids');
        is_string($feed_ids) && $feed_ids = explode(',', $feed_ids);
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $feeds = Feed::orderBy('id', 'DESC')
            ->where(function ($query) use ($request) {
                if ($request->max_id > 0) {
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->where(function ($query) use ($feed_ids) {
                if (count($feed_ids) > 0) {
                    $query->whereIn('id', $feed_ids);
                }
            })
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->take($limit)
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }


}
