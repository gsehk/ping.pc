<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\UserDatas;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class RankController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?: '1';
        
        /*switch ($type) {
            case '2':
                // 获取好友fids
                $fids = [];
                break;
            
            default:
                # code...
                break;
        }*/
        if ($type == 2) {
            $fids = [];
        }

        $ranList = $this->_getRankList($type, $fids = 0);
        // $list = $this->_parseRankList($ranList);

        return view('rank.index', ['type' => $type,'data' => $ranList]);
    }

    public function _getRankList($type, $fids)
    {
        if ($type == 2) {
            $follower_fids = $fids;
        }

        /*粉丝排行榜*/
        $followed = User::join('user_datas', 'users.id', '=', 'user_datas.user_id')
                    ->where('user_datas.key', '=', 'followed_count')
                    ->when($fids, function ($query) use ($fids) {
                        return $query->whereIn('user_datas.user_id', $fids);
                    })
                    ->select('users.id','users.name', 'user_datas.value')
                    ->with('datas')
                    ->orderBy('value', 'desc')
                    ->take(100)
                    ->get()->toArray();

        /*积分排行榜*/
        $credit = User::join('credit_user', 'users.id', '=', 'credit_user.uid')
                    ->select('users.id','users.name', 'credit_user.score')
                    ->with('datas')
                    ->orderBy('score', 'desc')
                    ->take(100)
                    ->get();

        /*内容发布排行榜*/
        $post = User::join('feed', 'users.id', '=', 'feed.user_id')
                    ->select('users.id','users.name')
                    ->with('datas')
                    ->orderBy('user_id', 'desc')
                    ->take(100)
                    ->get();
        dump( $credit->toArray());
    }
}
