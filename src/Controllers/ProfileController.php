<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;
use function zhiyi\Component\ZhiyiPlus\PlusComponentPc\replaceUrl;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class ProfileController extends BaseController
{

    /**
     * 个人中心首页
     *
     * @param  int|null $user_id 用户id
     * @return [type]            [description]
     */
    public function index(Request $request, int $user_id = 0)
    {
        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();

        // 粉丝
        $data['followers'] = $data['user']->followers()->with('datas')->orderBy('id', 'DESC')->take(9)->get();

        // 关注
        $data['followings'] = $data['user']->followings()->with('datas')->orderBy('id', 'DESC')->take(9)->get();

        return view('pcview::profile.index', $data, $this->PlusData);
    }

    /**
     * 用户中心文章
     *
     * @param  int|null    $user_id 用户id
     * @param  int|integer $type    文章类型
     * @return [type]               [description]
     */
    public function article(Request $request, User $model, int $user_id = 0)
    {
        $data['user'] = $user_id ? createRequest('GET', '/api/v2/users/' . $user_id) : $request->user();

        // 粉丝
        $data['followers'] = $data['user']->followers()->with('datas')->orderBy('id', 'DESC')->take(9)->get();

        // 关注
        $data['followings'] = $data['user']->followings()->with('datas')->orderBy('id', 'DESC')->take(9)->get();

        $data['type'] = 0;

        return view('pcview::profile.article', $data, $this->PlusData);
    }

    /**
     * 我的收藏
     */
    public function collect(Request $request)
    {
        $data['type'] = $request->input('type') ?: 1;

        return view('pcview::profile.collection', $data, $this->PlusData);
    }

    /**
     * 关联用户列表
     */
    public function follow(Request $request)
    {
        $data['type'] = $request->query('type') ?? 1;
        $data['user_id'] = $request->query('user_id') ?? 0;
        return view('pcview::profile.users', $data, $this->PlusData);
    }

    /**
     * 获取关联用户列表
     *
     * @param  int|integer $type    1:我的粉丝 2:关注的人 3:访客
     * @param  int|integer $user_id   用户id
     * @return [type]               [description]
     */
    public function followers(Request $request, User $model)
    {
        $user_id = $request->query('user_id') ?: 0;
        $api = $user_id && $user_id != $this->PlusData['TS']['id'] ? '/api/v2' . '/users/' . $user_id .'/followers' : '/api/v2/user/followers';

        $users = createRequest('GET', $api);
        $data['users'] = $users;
        $user = clone $users;
        $after = $user->pop()->id ?? 0;

        $html =  view('pcview::templates.follow', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'after' => $after,
            'data' => $html,
        ]);
    }

    /**
     * 获取关联用户列表
     *
     * @param  int|integer $type    1:我的粉丝 2:关注的人 3:访客
     * @param  int|integer $user_id   用户id
     * @return [type]               [description]
     */
    public function followings(Request $request, User $model)
    {
        $user_id = $request->query('user_id') ?: 0;
        $api = $user_id && $user_id != $this->PlusData['TS']['id'] ? '/api/v2' . '/users/' . $user_id .'/followings' : '/api/v2/user/followings';

        $users = createRequest('GET', $api);
        $data['users'] = $users;
        $user = clone $users;
        $after = $user->pop()->id ?? 0;

        $html =  view('pcview::templates.follow', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'after' => $after,
            'data' => $html,
        ]);
    }



    /**
     * 个人中心设置
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function account(Request $request, User $model)
    {
        return view('pcview::profile.account_new', [], $this->PlusData);


        // $user_id = $this->PlusData['TS']->id ?? 0;
        // $page = $request->page ?? 'account';
        // $datas['page'] = $page;
        // switch ($page) {
        //     case 'account':
        //         $user = $this->PlusData['TS'];
        //         $user['city'] = explode(' ', $user['location']);
        //         $datas['user'] = $user;
        //         break;
        //     case 'account-auth':
        //         $datas['auth'] = UserVerified::where('user_id', $user_id)
        //                         ->first();
        //         break;
        //     case 'account-security':
        //         # code...
        //         break;
        //     case 'account-bind':
        //         # code...
        //         break;
        // }

        // return view('pcview::profile.'.$page, $datas, $this->PlusData);
    }

    /**
     * 用户认证操作
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function doSaveAuth(Request $request)
    {
        $isVerif = UserVerified::where('user_id', $this->PlusData['TS']['id'])
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
        $verified = new UserVerified();
        $verified->user_id = $this->PlusData['TS']['id'];
        $verified->realname = $request->realname;
        $verified->idcard = $request->idcard;
        $verified->phone = $request->phone;
        $verified->info = $request->info ?: '';
        $verified->storage = $request->task_id ?? null;
        $verified->save();
        if ($request->task_id) {
            $fileWith = FileWith::find($request->task_id);
            if ($fileWith) {
                $fileWith->channel = 'verified:storage';
                $fileWith->raw = $verified->id;
                $fileWith->save();
            }
        }
        return response()->json([
            'status' => true
        ])->setStatusCode(200);
    }

    /**
     * 删除用户认证
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
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

    /**
     * 格式化分享数据
     *
     * @param  [type] $feeds    [description]
     * @param  [type] $uid      [description]
     * @param  string $template [description]
     * @return [type]           [description]
     */
    public function formatFeedList($feeds, $uid, $template = '')
    {
        $template = $template ?: 'pcview::templates.profile-feed';
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
            $data['feed']['storages'] = $feed->images->map(function ($images) {
                return ['storage_id' => $images->id, 'width' => explode('x', $images->size)[0], 'height' => explode('x', $images->size)[1]];
            });
            // 工具数据
            $data['tool'] = [];
            $data['tool']['feed_view_count'] = $feed->feed_view_count;
            $data['tool']['feed_digg_count'] = $feed->like_count;
            $data['tool']['feed_comment_count'] = $feed->feed_comment_count;
            // 暂时剔除当前登录用户判定
            $data['tool']['is_digg_feed'] = $feed->liked($uid);
            $data['tool']['is_collection_feed'] = $feed->collected($uid);
            // 最新3条评论
            $data['comments'] = [];

            $getCommendsNumber = 5;
            $data['comments'] = $feed->comments()
                ->orderBy('id', 'desc')
                ->take($getCommendsNumber)
                ->select(['id', 'user_id', 'created_at', 'comment_content', 'reply_to_user_id', 'comment_mark'])
                ->with('user')->get();
            $user = $feed->user()->select('id', 'name')->with('datas')->first();
            $data['user'] = $this->formatUserDatas($user);
            $datas[] = $data;
        }

        $feedList['data'] = $datas;
        $feedData['html'] = view($template, $feedList, $this->PlusData)->render();
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
     */
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
        $html = view('pcview::templates.profile-feed', $feeds, $this->PlusData)->render();

        return response()->json(static::createJsonData([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]));
    }

    /**
     * 资讯列表.
     */
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

    /**
     * 获取用户收藏列表
     *
     * @author zw
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getCollectionList(Request $request, int $user_id)
    {
        $uid = $this->PlusData['TS']['id'] ?? 0;
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
            $dataList['html'] = view('pcview::templates.profile-collect', $newsList, $this->PlusData)->render();
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
                    ->withCount(['likes' => function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    }])
                    ->byAudit()
                    ->with('images')
                    ->take($limit)
                    ->get();

            return $this->formatFeedList($feeds, $user_id, 'pcview::templates.feeds');
        }
    }
}
