<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Followed;
use Zhiyi\Plus\Models\StorageTask;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\UserVerified;

class ProfileController extends BaseController
{

    public function index(Request $request)
    {
        $type = $request->input('type') ?: 'all';

        return view('profile.index', ['type' => $type]);
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

    public function myFans(Request $request)
    {
        $data = [];
        $data['type'] = $type = $request->input('type') ?: 1;

        if (!$this->mergeData['TS']) {
            return redirect(route('pc:index'));
        }
        $user_id = $this->mergeData['TS']['id'];

        // 我的粉丝
        if ($type == 1) {
            $followeds = Followed::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->with('userFollowing', 'user.datas')
                ->paginate(15);

            $data['followeds'] = [];
            foreach ($followeds as $followed) {
                $_data = [];
                $_data['id'] = $followed->id;

                // 获取用户设置
                $_user = $followed->user->toArray();
                $_data['user']['id'] = $_user['id'];
                $_data['user']['phone'] = $_user['phone'];
                // $_data['user']['followed'] = $followed->user->counts()->where('key', 'followed_count')->value('value');
                $_followed = null;
                $_followed = $followed->user->counts->map( function($count) {
                    if($count->key == 'followed_count') {
                        return $count->value;
                    }
                });
                var_dump($_followed);
                foreach ($_user['datas'] as $key => $value) {
                    $_data['user'][$value['profile']] = $value['pivot']['user_profile_setting_data'];
                }

                $_data['my_follow_status'] = $followed->userFollowing->where('following_user_id', $followed->followed_user_id)->isEmpty() ? 0 : 1;

                $_data['follow_status'] = 1; //关注我的的列表  对方关注状态始终为1
                $data['followeds'][] = $_data;
            }
        } else { // 关注的人
            $follows = Following::where('user_id', $user_id)
                ->orderBy('id', 'DESC')
                ->with('userFollowed')
                ->get();
            $datas['follows'] = [];
            foreach ($follows as $follow) {
                $_data = [];
                $_data['id'] = $follow->id;
                $_data['user'] = $follow->following_user_id;
                $_data['my_follow_status'] = 1; //我关注的列表  关注状态始终为1
                $_data['follow_status'] = $follow->userFollowed->where('followed_user_id', $follow->following_user_id)->isEmpty() ? 0 : 1;
                $data['follows'][] = $_data;
            }
        }

        return view('pcview::profile.myfans', $data, $this->mergeData);
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
