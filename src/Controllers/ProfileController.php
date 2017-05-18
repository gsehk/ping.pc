<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use DB;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\UserDatas;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\Following;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Plus\Models\Area;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedStorage;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;

class ProfileController extends BaseController
{

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
        }

        // 地区
        if ($data['user']['province']) {
            $data['user']['province'] = Area::where('id', $data['user']['province'])
                                        ->value('name');
        }
        if ($data['user']['city']) {
            $data['user']['city'] = Area::where('id', $data['user']['city'])
                                        ->value('name');
        }
        if ($data['user']['area']) {
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

        return view('pcview::profile.index', $data, $this->mergeData);
    }

    public function article(Request $request)
    {
        $type = $request->input('type') ?: 'relase';

        return view('profile.article', ['type' => $type]);
    }

    public function collection(Request $request)
    {
        $type = $request->input('type') ?: 1;

        return view('profile.collection', ['type' => $type]);
    }

    public function users(Request $request)
    {
        $data = [];
        $data['type'] = $type = $request->input('type') ?: 1;

        if (!$this->mergeData['TS']) {
            return redirect(route('pc:index'));
        }
        $user_id = $this->mergeData['TS']['id'];

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
                $_data['my_follow_status'] = $follow->userFollowing->where('following_user_id', $follow->follow_user_id)->isEmpty() ? 0 : 1;
                // 最新微博图片
                $_data['storages'] = FeedStorage::join('feeds', 'feed_storages.feed_id', '=', 'feeds.id')
                                        ->where('feeds.user_id', '=', $follow->user->id)
                                        ->orderBy('feed_storages.id', 'desc')
                                        ->take(3)
                                        ->pluck('feed_storages.feed_storage_id')
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
                                        ->pluck('feed_storages.feed_storage_id')
                                        ->toArray();

                $data['datas'][] = $_data;
            }
            $data['page'] = $follows->appends(['type'=>$type])->links('pcview::template.page');
        } else if ($type == 3) { // 访客

        } else { // 推荐用户 
            $recusers = UserDatas::where('key', 'feeds_count')
            ->where('user_id', '!=', $this->mergeData['TS']['id'])
            ->with('user.datas')
            ->orderBy(DB::raw('-value', 'desc'))
            ->paginate(1);

            $data['datas'] = [];
            foreach ($recusers as $recuser) {
                $_data['user'] = $this->formatUserDatas($recuser->user);
                if ($this->mergeData['TS']) {
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
                                        ->pluck('feed_storages.feed_storage_id')
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
                $datas['data'] = User::where('id', $user_id)
                                ->select('id', 'name')
                                ->with('datas')
                                ->first();
                foreach ($datas['data']['datas'] as $key => &$value) {
                    $datas['data'][$value['profile']] = $value['pivot']['user_profile_setting_data'];
                }
                unset($datas['data']['datas']);
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
        
        return view('profile.'.$datas['page'], $datas, $this->mergeData);
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
        $verif->storage = $storage_id ?: '';
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

}
