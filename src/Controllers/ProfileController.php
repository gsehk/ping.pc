<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Models\Area;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVisitor;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedStorage;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;

class ProfileController extends BaseController
{

    /**
     * 个人中心首页
     * 
     * @author zw
     * @date   2017-05-20
     * @param  Request    $request [description]
     * @return [type]              [description]
     */
    public function index(Request $request)
    {

        $type = $request->input('type') ?: 'all';
        $user_id = $request->input('user_id') ?: $this->mergeData['TS']['id'];

        if (!empty($this->mergeData['TS']) && $this->mergeData['TS']['id'] == $user_id) {
            $data['user'] = $this->mergeData['TS'];
        } else {
            $user = User::where('id', $user_id)
                        ->with('datas', 'counts')
                        ->first();
            $data['user'] = $this->formatUserDatas($user);

            // 是否关注
            $data['my_follow_status'] = Followed::where('followed_user_id', $this->mergeData['TS']['id'])
                                            ->where('user_id', $user_id)
                                            ->first() ? 1 : 0;

            // 访客
            if (!empty($this->mergeData['TS'])) {
                $visitor = UserVisitor::where([['user_id', $this->mergeData['TS']['id']], ['to_uid', $user_id]])->first();
                if ($visitor) {
                    $visitor->save();
                }else{
                    $visitor = new UserVisitor();
                    $visitor->user_id = $this->mergeData['TS']['id'];
                    $visitor->to_uid = $user_id;
                    $visitor->save();
                }
            }
        }

        // 地区
        if (!empty($data['user']['province'])) {
            $data['user']['province'] = Area::where('id', $data['user']['province'])
                                        ->value('name');
        }
        if (!empty($data['user']['city'])) {
            $data['user']['city'] = Area::where('id', $data['user']['city'])
                                        ->value('name');
        }
        if (!empty($data['user']['area'])) {
            $data['user']['area'] = Area::where('id', $data['user']['area'])
                                        ->value('name');
        }

        $data['type'] = $type;

        // 粉丝
        $followeds = Followed::where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->with('user.datas')
            ->take(9)
            ->get();
        foreach ($followeds as $followed) {
            $data['followeds'][] = $this->formatUserDatas($followed->user);
        }

        // 关注
        $followings = Following::where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->with('user.datas')
            ->take(9)
            ->get();
        foreach ($followings as $following) {
            $data['followings'][] = $this->formatUserDatas($following->user);
        }
        // 访客
        $visitors = UserVisitor::where('to_uid', $user_id)
                ->with('user.datas')
                ->take(9)
                ->get();
        foreach ($visitors as $visitor) {
            $data['visitors'][] = $this->formatUserDatas($visitor->user);
        }

        return view('pcview::profile.index', $data, $this->mergeData);
    }

    /**
     * 个人中心文章栏
     * 
     * @author zw
     * @date   2017-05-20
     * @param  Request    $request [description]
     * @return [type]              [description]
     */
    public function article(Request $request)
    {
        $type = $request->input('type') ?: 0;
        $user_id = $request->input('user_id') ?: $this->mergeData['TS']['id'];

        if (!empty($this->mergeData['TS']) && $this->mergeData['TS']['id'] == $user_id) {
            $data['user'] = $this->mergeData['TS'];
        } else {
            $user = User::where('id', $user_id)
                        ->with('datas', 'counts')
                        ->first();
            $data['user'] = $this->formatUserDatas($user);
        }

        // 地区
        if (!empty($data['user']['province'])) {
            $data['user']['province'] = Area::where('id', $data['user']['province'])
                                        ->value('name');
        }
        if (!empty($data['user']['city'])) {
            $data['user']['city'] = Area::where('id', $data['user']['city'])
                                        ->value('name');
        }
        if (!empty($data['user']['area'])) {
            $data['user']['area'] = Area::where('id', $data['user']['area'])
                                        ->value('name');
        }

        $data['type'] = $type;

        // 粉丝
        $followeds = Followed::where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->with('user.datas')
            ->take(9)
            ->get();
        foreach ($followeds as $followed) {
            $data['followeds'][] = $this->formatUserDatas($followed->user);
        }

        // 关注
        $followings = Following::where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->with('user.datas')
            ->take(9)
            ->get();
        foreach ($followings as $following) {
            $data['followings'][] = $this->formatUserDatas($following->user);
        }

        return view('pcview::profile.article', $data, $this->mergeData);
    }

    /**
     * 我的收藏
     * 
     * @author zw
     * @date   2017-05-20
     * @param  Request    $request [description]
     * @return [type]              [description]
     */
    public function collection(Request $request)
    {
        $data['type'] = $request->input('type') ?: 1;
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
            ->orderBy(DB::raw('-value', 'desc'))->take(5)->get();

        foreach ($_rec_users as $_rec_user) {
            $data['rec_users'][] = $this->formatUserDatas($_rec_user->user);
        }

        return view('pcview::profile.collection', $data, $this->mergeData);
    }

    public function users(Request $request)
    {
        $data = [];
        $data['type'] = $type = $request->input('type') ?: 1;
        $data['user_id'] = $user_id = $request->input('user_id') ?: $this->mergeData['TS']['id'];

        // 我的粉丝
        if ($type == 1) {
            $follows = Followed::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->with('userFollowing', 'user.datas', 'user.counts')
                ->paginate(6);

            $data['datas'] = [];
            foreach ($follows as $follow) {
                $_data = [];
                $_data['id'] = $follow->id;
                // 获取用户信息
                $_data['user'] = $this->formatUserDatas($follow->user);
                // 关注状态
                $_data['my_follow_status'] = $follow->userFollowing->where('following_user_id', $follow->followed_user_id)->isEmpty() ? 0 : 1;
                // 最新微博图片
                $_data['storages'] = FeedStorage::join('feeds', 'feed_storages.feed_id', '=', 'feeds.id')
                                        ->where('feeds.user_id', '=', $follow->user->id)
                                        ->orderBy('feed_storages.id', 'desc')
                                        ->take(3)
                                        ->select('feeds.id', 'feed_storages.feed_storage_id')
                                        ->get()
                                        ->toArray();

                $data['datas'][] = $_data;
            }
            $data['page'] = $follows->appends(['type'=>$type])->links('pcview::template.page');
        } else if ($type == 2) { // 关注的人
            $follows = Following::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->with('userFollowed', 'user.datas', 'user.counts')
                ->paginate(6);
            $data['datas'] = [];
            foreach ($follows as $follow) {
                $_data = [];
                $_data['id'] = $follow->id;
                // 获取用户信息
                $_data['user'] = $this->formatUserDatas($follow->user);
                // 关注状态
                $_data['my_follow_status'] = 1; //我关注的列表  关注状态始终为1
                // 最新微博图片
                $_data['storages'] = FeedStorage::join('feeds', 'feed_storages.feed_id', '=', 'feeds.id')
                                        ->where('feeds.user_id', '=', $follow->user->id)
                                        ->orderBy('feed_storages.id', 'desc')
                                        ->take(3)
                                        ->select('feeds.id', 'feed_storages.feed_storage_id')
                                        ->get()
                                        ->toArray();

                $data['datas'][] = $_data;
            }
            $data['page'] = $follows->appends(['type'=>$type])->links('pcview::template.page');
        } else if ($type == 3) { // 访客
            $visitors = UserVisitor::where('to_uid', $this->mergeData['TS']['id'])
                ->with('user.datas')
                ->paginate(6);
            $data['datas'] = [];
            $data['count'] = UserVisitor::where('to_uid', $this->mergeData['TS']['id'])->count();
            foreach ($visitors as $visitor) {
                $_data['user'] = $this->formatUserDatas($visitor->user);
                if (!$this->mergeData['TS']) {
                    $_data['my_follow_status'] = 0;
                } else {
                    $_data['my_follow_status'] = Following::where('following_user_id', $visitor->user->id)
                                                    ->where('user_id', $this->mergeData['TS']['id'])
                                                    ->get()
                                                    ->isEmpty() ? 0 : 1;
                }
                // 最新微博图片
                $_data['storages'] = FeedStorage::join('feeds', 'feed_storages.feed_id', '=', 'feeds.id')
                                        ->where('feeds.user_id', '=', $visitor->user->id)
                                        ->orderBy('feed_storages.id', 'desc')
                                        ->take(3)
                                        ->select('feeds.id', 'feed_storages.feed_storage_id')
                                        ->get()
                                        ->toArray();

                $data['datas'][] = $_data;
            }
            $data['page'] = $visitors->appends(['type'=>$type])->links('pcview::template.page');

        } else { // 推荐用户 
            $recusers = UserDatas::where('key', 'feeds_count')
            ->where('user_id', '!=', $this->mergeData['TS']['id'])
            ->with('user.datas')
            ->orderBy(DB::raw('-value', 'desc'))
            ->paginate(6);

            $data['datas'] = [];
            foreach ($recusers as $recuser) {
                $_data['user'] = $this->formatUserDatas($recuser->user);
                if (!$this->mergeData['TS']) {
                    $_data['my_follow_status'] = 0;
                } else {
                    $_data['my_follow_status'] = Following::where('following_user_id', $recuser->user->id)
                                                    ->where('user_id', $this->mergeData['TS']['id'])
                                                    ->get()
                                                    ->isEmpty() ? 0 : 1;
                }
                // 最新微博图片
                $_data['storages'] = FeedStorage::join('feeds', 'feed_storages.feed_id', '=', 'feeds.id')
                                        ->where('feeds.user_id', '=', $recuser->user->id)
                                        ->orderBy('feed_storages.id', 'desc')
                                        ->take(3)
                                        ->select('feeds.id', 'feed_storages.feed_storage_id')
                                        ->get()
                                        ->toArray();

                $data['datas'][] = $_data;
            }
            $data['page'] = $recusers->appends(['type'=>$type])->links('pcview::template.page');
        }

        return view('pcview::profile.users', $data, $this->mergeData);
    }

    public function rank()
    {

        return view('profile.rank');
    }

    public function account(Request $request)
    {
        $user_id = $this->mergeData['TS']['id'] ?? 0;
        $datas['page'] = $request->input('page') ?: 'account';
        switch ($datas['page']) {
            case 'account':
                $info = User::where('id', $user_id)
                                ->select('id', 'name')
                                ->with('datas')
                                ->first();
                $datas['info'] = $this->formatUserDatas($info);
                break;
            case 'account-auth':
                $datas['auth'] = UserVerified::where('user_id', $user_id)
                                ->first();
                break;
            case 'account-security':
                # code...
                break;
            case 'account-bind':
                # code...
                break;
        }
        
        return view('pcview::profile.'.$datas['page'], $datas, $this->mergeData);
    }

    public function score(Request $request)
    {
        $type = $request->input('type') ?: '1';

        return view('profile.scoredetail', ['type' => $type]);
    }


    public function doSaveAuth(Request $request)
    {
        $isVerif = UserVerified::where('user_id', $this->mergeData['TS']['id'])
                    ->count();
        if ($isVerif) {
            return response()->json([
                'status' => false,
                'message' => '您已提交认证资料,请勿重复提交',
            ])->setStatusCode(202);
        }
        if (!$request->realname) {
            return response()->json([
                'status' => false,
                'message' => '真实姓名不能为空',
            ])->setStatusCode(201);
        }
        if (!$request->phone) {
            return response()->json([
                'status' => false,
                'message' => '联系方式错误',
            ])->setStatusCode(201);
        }
        if (!$request->idcard) {
            return response()->json([
                'status' => false,
                'message' => '身份证号码错误',
            ])->setStatusCode(201);
        }

        if ($request->task_id) {
            $storage = StorageTask::where('id', $request->task_id)
                    ->select('hash')
                    ->with('storage')
                    ->first();
            if ($storage) {
                $storage_id = $storage['storage']['id'];
            }
        }
        $verif = new UserVerified();

        $verif->user_id = $this->mergeData['TS']['id'];
        $verif->realname = $request->realname;
        $verif->idcard = $request->idcard;
        $verif->phone = $request->phone;
        $verif->info = $request->info ?: '';
        $verif->storage = $storage_id ?? null;
        $verif->save();

        return response()->json([
            'status' => true
        ])->setStatusCode(200);        
    }

    public function delUserAuth(Request $request)
    {
        $user_id = $request->input('user_id');

        $response =  UserVerified::where('user_id', $user_id)->delete();

        return response()->json([
            'uri' => '/profile/account?page=account-auth',
            'code' => $response,
            'status' => true
        ])->setStatusCode(200);  
    }

    public function formatFeedList($feeds, $uid, $template = '')
    {
        $template = $template ?: 'pcview::template.profile-feed';
        $datas = [];
        foreach ($feeds as $feed) {
            $data = [];
            $data['user_id'] = $feed->user_id;
            $data['feed_mark'] = $feed->feed_mark;
            // 动态数据
            $data['feed'] = [];
            $data['feed']['feed_id'] = $feed->id;
            $data['feed']['feed_title'] = $feed->feed_title ?? '';
            $data['feed']['feed_content'] = replaceUrl($feed->feed_content);
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
                ->with('user')
                ->get()
                ->toArray();
            $user = $feed->user()
                ->select('id', 'name')
                ->with('datas')
                ->first();
            $data['user'] = $this->formatUserDatas($user);
            $datas[] = $data;
        }

        $feedList['data'] = $datas;
        $feedData['html'] = view($template, $feedList, $this->mergeData)->render();
        $feedData['maxid'] = count($datas)>0 ? $datas[count($datas)-1]['feed']['feed_id'] : 0;

        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '动态列表获取成功',
                'data' => $feedData,
            ])->setStatusCode(200);
    }

    /**
     * 获取单个用户的动态列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUserFeeds(Request $request, int $user_id)
    {
        $auth_id = $this->mergeData['TS']['id'] ?? 0;
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));
        $type = $request->input('type');

        if ($type == 'all') {
            $feeds = Feed::orderBy('id', 'DESC')
            ->where('user_id', $user_id)
            ->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('id', '<', $max_id);
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
        } else {
            $feeds = Feed::join('feed_storages', 'feeds.id', '=', 'feed_storages.feed_id')
            ->orderBy('id', 'DESC')
            ->where('user_id', $user_id)
            ->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('feeds.id', '<', $max_id);
                }
            })
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->groupBy('id')
            ->with('storages')
            ->take($limit)
            ->get();
        }

        return $this->formatFeedList($feeds, $auth_id);
    }

    /**
     * 资讯列表.
     * 
     * @param  $state [文章状态]
     * @return mixed 返回结果
     */
    public function getNewsList(Request $request, int $user_id)
    {
        $uid = $this->mergeData['TS']['id'] ?? 0;
        $state = $request->type;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        $datas = News::where('audit_status', $state)
                ->where('author', $user_id)
                ->where(function ($query) use ($max_id) {
                    if ($max_id > 0) {
                        $query->where('id', '<', $max_id);
                    }
                })
                ->orderBy('id', 'desc')
                ->select('id','title','updated_at','storage','comment_count','hits','from','subject','audit_status')
                ->withCount('collection')
                ->with('comments')
                ->take($limit)
                ->get();
        foreach ($datas as $key => &$value) {
            $value['is_collection_news'] = $uid ? NewsCollection::where('user_id', $uid)->where('news_id', $value['id'])->count() : 0;
            // $value['is_digg_news'] = $uid ? NewsDigg::where('user_id', $uid)->where('news_id', $value['id'])->count() : 0;
        }
        $newsList['data'] = $datas;
        $newsData['html'] = view('pcview::template.profile-news', $newsList, $this->mergeData)->render();
        $newsData['maxid'] = count($datas)>0 ? $datas[count($datas)-1]['id'] : 0;

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $newsData
        ]))->setStatusCode(200);
    }

    /**
     * 获取用户收藏列表
     *  
     * @author zw
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getCollectionList(Request $request, int $user_id)
    {
        $uid = $this->mergeData['TS']['id'] ?? 0;
        $type = $request->type;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;
        if ($type == 'news') {
            $datas = News::whereIn('id', NewsCollection::where('user_id', $user_id)->pluck('news_id'))
            ->where(function ($query) use ($max_id) {
                if ($max_id > 0) {
                    $query->where('id', '<', $max_id);
                }
            })
            ->orderBy('id', 'desc')
            ->select('id','title','updated_at','storage','comment_count','hits','from','subject', 'audit_status')
            ->withCount('collection')
            /*->with(['comments' => function($query){
                    $query->take(3);
            }])*/
            ->with(['storage', 'comments'])
            ->get();
            foreach ($datas as $key => &$value) {
                $value['is_collection_news'] = $uid ? NewsCollection::where('user_id', $uid)->where('news_id', $value['id'])->count() : 0;
                // $value['is_digg_news'] = $uid ? NewsDigg::where('user_id', $uid)->where('news_id', $value['id'])->count() : 0;
            }
            $newsList['data'] = $datas;
            $dataList['html'] = view('pcview::template.profile-collect', $newsList, $this->mergeData)->render();
            $dataList['maxid'] = count($datas)>0 ? $datas[count($datas)-1]['id'] : 0;

            return response()->json(static::createJsonData([
                'status'  => true,
                'code'    => 0,
                'message' => '获取成功',
                'data'    => $dataList
            ]))->setStatusCode(200);

        } else {
            $feeds = Feed::orderBy('id', 'DESC')
                    ->where(function ($query) use ($max_id, $user_id) {
                        $query->whereIn('id', FeedCollection::where('user_id', $user_id)->pluck('feed_id'));
                        if ($max_id > 0) {
                            $query->where('id', '<', $max_id);
                        }
                    })
                    ->withCount(['diggs' => function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }])
                    ->byAudit()
                    ->with('storages')
                    ->take($limit)
                    ->get();

            return $this->formatFeedList($feeds, $user_id, 'pcview::template.feed');      
        }
    }

    public function cropper()
    {
        return view('pcview::profile.cropper', $this->mergeData);
    }
}
