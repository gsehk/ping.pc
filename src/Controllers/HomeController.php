<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditSetting;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditRecord;
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
        $data['credit'] = CreditSetting::where('name', 'check_in')->first();
        $data['ischeck'] = CheckInfo::where('created_at', '>', Carbon::today())
                    ->where(function($query){
                        if ($this->mergeData) {
                            $query->where('user_id', $this->mergeData['TS']['id']);
                        }
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
        $data['checkin'] = CheckInfo::where(function($query){
                                if ($this->mergeData) {
                                    $query->where('user_id', $this->mergeData['TS']['id']);
                                }
                            })
                            ->first();
        dump(Carbon::today()->timestamp);
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
        $user_id = $this->mergeData['TS']['id'] ?? 0;
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
        $user_id = $this->mergeData['TS']['id'] ?? 0;
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
        $user_id = $this->mergeData['TS']['id'] ?? 0;
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

    /**
     * 签到
     * 
     * @author zw
     * @date   2017-05-16
     * @return state
     */
    public function checkin()
    {
        $user_id = $this->mergeData['TS']['id'] ?? 0;

        $check_info = CheckInfo::where('user_id', $user_id)
                    ->where('created_at', '>', Carbon::today())
                    ->first();
        // 未签到
        if (!$check_info) {

            $last = CheckInfo::where('created_at', '<', Carbon::today())
                    ->where('user_id', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();
            $data['user_id'] = $user_id;
            $data['created_at'] = Carbon::now();
            // 是否有签到记录
            if ($last) {
                // 是否是连续签到
                if (Carbon::parse($last['created_at'])->timestamp > (Carbon::today()->timestamp - 86400)) {
                    $data['con_num'] = $last['con_num'] + 1;
                } else {
                    $data['con_num'] = 1;
                }
                $data['total_num'] = $last['total_num'] + 1;
            } else {
                $data['con_num'] = 1;
                $data['total_num'] = 1;
            }

            $check_info = new CheckInfo();
            $check_info->user_id = $user_id;
            $check_info->con_num = $data['con_num'];
            $check_info->total_num = $data['total_num'];

            if ($check_info->save()) {
                $credit_user = $this->setUserCredit($user_id, 'check_in', 1);
                // 更新连续签到和累计签到的数据
                $totalnum = UserDatas::where('user_id', $user_id)
                            ->where('key', 'check_totalnum')
                            ->first();
                if ($totalnum) {
                    $totalnum->value = $data['total_num'];
                    $totalnum->save();
                } else {
                    $userData = new UserDatas();
                    $userData->user_id = $user_id;
                    $userData->key = 'check_totalnum';
                    $userData->value = $data['total_num'];
                    $userData->save();
                }
            }

            return response()->json([
                'status'  => true,
                'message' => '签到成功',
                'data' => $credit_user
            ])->setStatusCode(200);
        }
    }

    /**
     * 设置用户积分
     * 
     * @author zw
     * @date   2017-05-16
     * @param  int        $user_id 用户ID
     * @param  string     $action  系统设定的积分规则的名称
     * @param  [type]     $type    reset:按照操作的值直接重设积分值，整型：作为操作的系数，-1可实现增减倒置
     */
    public function setUserCredit(int $user_id, string $action, $type)
    {
        if (!$user_id) {

            return false;
        }

        $credit_ruls = CreditSetting::where('name', $action)->first();
        if (!$credit_ruls) {

            return false;
        }

        $credit_user = CreditUser::where('user_id', $user_id)->first();

        if ($type == 'reset') {
            # code...
        } else {
            if ($credit_user) {
                $credit_user->score = $credit_user->score + $credit_ruls->score;
                $credit_user->save();
            } else {
                $creditUser = new CreditUser();
                $creditUser->user_id = $user_id;
                $creditUser->score = $credit_ruls->score;
                $creditUser->save();
            }
            if ($credit_ruls->score > 0) {
                $change = '<font color="lightskyblue">'.$credit_ruls->score.'</font>">';
            } else {
                $change = '<font color="red">'.$credit_ruls->score.'</font>">';
            }
            $credit_record = new CreditRecord();
            $credit_record->cid = $credit_ruls->id;
            $credit_record->user_id = $user_id;
            $credit_record->action = $credit_ruls->alias;
            $credit_record->change = $change;
            $credit_record->detail = json_encode(['score'=>$credit_ruls->score]);
            $credit_record->save();
        }
        return $credit_user;
    }
}
