<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditSetting;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditRecord;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services\FeedCount;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
    	$data['type'] = $request->input('type') ?: ($this->mergeData['TS'] ? 1 : 2);

        // 积分
        $data['credit'] = CreditSetting::where('name', 'check_in')->first();

        // 签到
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
            })->orderBy('created_at', 'desc')->first();

        // 推荐用户
        $_rec_users = UserDatas::where('key', 'feeds_count')
        ->where('user_id', '!=', $this->mergeData['TS']['id'])
        ->select('id', 'user_id')
        ->with('user.datas')
        ->orderBy(DB::raw('-value', 'desc'))
        ->take(9)
        ->get();
        foreach ($_rec_users as $_rec_user) {
            $data['rec_users'][] = $this->formatUserDatas($_rec_user->user);
        }

    	return view('pcview::home.index', $data, $this->mergeData);
    }

    public function read(Request $request, int $feed_id)
    {
        $uid = $this->mergeData['TS']['id'] ?? 0;
        if (! $feed_id) {

            return response('动态ID不能为空');
        }
        $feed = Feed::byFeedId($feed_id)->with('storages')->with('user.datas')->first();

        if (! $feed) {
            return response('动态不存在或已被删除');
        }
        
        // 组装数据
        $data = [];
        // 用户标识
        $data['user_id'] = $feed->user_id;
        // 动态内容
        $data['feed'] = [];
        $data['feed']['feed_id'] = $feed->id;
        $data['feed']['feed_title'] = $feed->feed_title ?: '';
        $data['feed']['feed_content'] = $feed->feed_content;
        $data['feed']['share_desc'] = str_replace(PHP_EOL, '', substr($feed->feed_content, 0, 60));
        $data['feed']['created_at'] = $feed->created_at->toDateTimeString();
        $data['feed']['feed_from'] = $feed->feed_from;
        $data['feed']['storages'] = $feed->storages->map(function ($storage) {
            return ['storage_id' => $storage->id, 'width' => $storage->image_width, 'height' => $storage->image_height];
        });

        // 工具栏数据
        $data['tool'] = [];
        $data['tool']['feed_view_count'] = $feed->feed_view_count;
        $data['tool']['feed_digg_count'] = $feed->feed_digg_count;
        $data['tool']['feed_comment_count'] = $feed->feed_comment_count;
        $data['tool']['feed_collection_count'] = count($feed->collection);
        // 暂时剔除当前登录用户判定
        $data['tool']['is_digg_feed'] = $uid ? FeedDigg::byFeedId($feed->id)->byUserId($uid)->count() : 0;
        $data['tool']['is_collection_feed'] = $uid ? FeedCollection::where('feed_id', $feed->id)->where('user_id', $uid)->count() : 0;
        // 动态评论,详情默认为空，自动获取评论列表接口
        $data['comments'] = [];
        // 分享者发布的文章信息
        $news = News::where('author', $feed->user_id)
                ->withCount('newsCount') //当前作者文章数
                ->with('user.datas')
                ->first();
        if ($news) {
            $data['news']['user'] = $this->formatUserDatas($news->user); 
            $data['news']['newsNum'] = $news->news_count_count;
            $data['news']['list'] = $news->where('author', $feed->user_id)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->select('id','title')
                ->get();
            $data['news']['hotsNum'] = $news->join('news_cates_links', 'news.id','=','news_cates_links.news_id')
                ->where([['news.author','=',$feed->user_id], ['news_cates_links.cate_id','=',1]])
                ->count();
        }else{
            $data['news']['user'] = $this->formatUserDatas($feed->user);
            $data['news']['newsNum'] = 0;
            $data['news']['hotsNum'] = 0;
            $data['news']['list'] = [];
        }
        
        Feed::byFeedId($feed_id)->increment('feed_view_count');

        return view('pcview::home.read',$data, $this->mergeData);
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

            $getCommendsNumber = 3;
            $data['comments'] = $feed->comments()
                ->orderBy('id', 'desc')
                ->take($getCommendsNumber)
                ->select(['id', 'user_id', 'created_at', 'comment_content', 'feed_id', 'reply_to_user_id', 'comment_mark'])
                ->with('user')
                ->get();
            $user = $feed->user()->select('id', 'name')->with('datas')->first();
            $data['user'] = $this->formatUserDatas($user); 
            $datas[] = $data;
        }
        $feedList['data'] = $datas;
        $feedData['html'] = view('pcview::template.feed', $feedList, $this->mergeData)->render();
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
                    // 更新总签到数
                    $totalnum->value = $data['total_num'];
                    $totalnum->save();
                    // 更新连续签到数
                    $totalnum->where([['user_id', $user_id], ['key', 'check_connum']]);
                    $totalnum->save();
                } else { 
                    // 第一次写入
                    $userData = new UserDatas();
                    $userData->user_id = $user_id;
                    $userData->key = 'check_totalnum';
                    $userData->value = $data['total_num'];
                    $userData->save();

                    $userData->user_id = $user_id;
                    $userData->key = 'check_connum';
                    $userData->value = $data['con_num'];
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
                $credit_user = new CreditUser();
                $credit_user->user_id = $user_id;
                $credit_user->score = $credit_ruls->score;
                $credit_user->save();
            }
            if ($credit_ruls->score > 0) {
                $change = '<font color="lightskyblue">'.$credit_ruls->score.'</font>';
            } else {
                $change = '<font color="red">'.$credit_ruls->score.'</font>';
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

    /**
     * 查看一条微博的评论列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $feed_id [description]
     * @return [type]           [description]
     */
    public function getFeedCommentList(Request $request, int $feed_id)
    {
        $limit = $request->get('limit', '15');
        $max_id = intval($request->get('max_id'));
        if (! $feed_id) {
            return response()->json([
                'status' => false,
                'code' => 6003,
                'message' => '动态ID不能为空',
            ])->setStatusCode(400);
        }
        $comments = FeedComment::byFeedId($feed_id)
            ->take($limit)->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('id', '<', $max_id);
                }
            })
            ->with('user.datas')
            ->select(['id', 'created_at', 'comment_content', 'user_id', 'feed_id', 'to_user_id', 'reply_to_user_id', 'comment_mark'])
            ->orderBy('id', 'desc')
            ->get();
        foreach ($comments as $key => $value) {
            $value['info'] = $this->formatUserDatas($value['user']);
            unset($value['user']);
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

    public function getFeedInfo(Request $request, int $feed_id) 
    {
        $user_id = $this->mergeData['TS']['id'];

        $feed = Feed::orderBy('id', 'DESC')
            ->whereIn('user_id', array_merge([$user_id], $request->user()->follows->pluck('following_user_id')->toArray()))
            ->where('id', $feed_id)
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->get();

        return $this->formatFeedList($feed, $user_id);
    }

}
