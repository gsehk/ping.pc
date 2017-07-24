<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\UserDatas;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CreditUser;

class RankController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->input('type') ?: '1';
        
        if ($type == 2) {
            // get friend fids
            $fids = [];
        }

        $ranList = $this->_getRankList($type, $fids = 0);

        $list = $this->_parseRankList($ranList);
        $list['type'] = $type;
        
        return view('pcview::rank.index', $list, $this->PlusData);
    }

    public function _getRankList($type, $fids)
    {
        $user_id = $this->PlusData['TS']['id'] ?? 0;
        $rank_list_key = 'user_rank_list_'.$type;
        $rank_list = Cache::get($rank_list_key);
        // if ($rank_list) {
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
                        ->get();

            /*积分排行榜*/
            $credit = User::join('credit_user', 'users.id', '=', 'credit_user.user_id')
                        ->when($fids, function ($query) use ($fids) {
                            return $query->whereIn('credit_user.user_id', $fids);
                        })
                        ->select('users.id','users.name', 'credit_user.score')
                        ->with('datas')
                        ->orderBy('score', 'desc')
                        ->take(100)
                        ->get();

            /*内容发布排行榜join('feeds', 'users.id', '=', 'feeds.user_id')*/
            $post = User::join('user_datas', 'users.id', '=', 'user_datas.user_id')
                        ->where('user_datas.key', '=', 'feeds_count')
                        ->when($fids, function ($query) use ($fids) {
                            return $query->whereIn('user_datas.user_id', $fids);
                        })
                        ->select('users.id','users.name', 'user_datas.value')
                        ->with('datas')
                        ->orderBy('value', 'desc')
                        ->take(100)
                        ->get();

           /* $post = Feed::where('audit_status', '=', 1)
                        ->when($fids, function ($query) use ($fids) {
                            return $query->whereIn('user_id', $fids);
                        })
                        ->select('user_id', DB::raw('count(*) as total'))
                        ->groupBy('user_id')
                        ->with('user.datas')
                        ->orderBy('total', 'desc')
                        ->take(100)
                        ->get();*/

            /*累计签到排行榜*/
            $check = User::join('user_datas', 'users.id', '=', 'user_datas.user_id')
                        ->where('user_datas.key', '=', 'check_totalnum')
                        ->when($fids, function ($query) use ($fids) {
                            return $query->whereIn('user_datas.user_id', $fids);
                        })
                        ->select('users.id','users.name', 'user_datas.value')
                        ->with('datas')
                        ->orderBy('value', 'desc')
                        ->take(100)
                        ->get();
            /*$check = CheckInfo::
                        when($fids, function ($query) use ($fids) {
                            return $query->whereIn('user_id', $fids);
                        })
                        ->select('user_id', DB::raw('count(*) as total'))
                        ->with('user.datas')
                        ->groupBy('user_id')
                        ->orderBy('total', 'desc')
                        ->get();*/
            $rank_list = [
                'followedrank' => $followed,
                'creditrank' => $credit,
                'postrank' => $post,
                'checktotalrank' => $check
            ];

            Cache::put($rank_list_key, $rank_list, 600);
        // }

        $user_rank_key = 'user_rank_list_'.$type.'_'.$user_id;
        $user_rank_list = Cache::get($user_rank_key);
        // if ($user_rank_list) {
            $user_credit = CreditUser::where('user_id', $user_id)->first();
            $user_data = UserDatas::where('user_id', $user_id)
                        ->select('key', 'value')
                        ->get();
            $user_key_data = [];
            foreach ($user_data as $k => $v) {
                $user_key_data[$v->key] = $v->value;
            }

            $followed_rank = UserDatas::where(function ($query) use ($user_key_data){
                                if (!empty($user_key_data['followed_count'])) {
                                    $query->where('value', '>', $user_key_data['followed_count']);
                                }
                            })
                            ->where('key', 'followed_count')
                            ->count();
            $followed_rank += 1;

            $post_rank = UserDatas::where(function ($query) use ($user_key_data){
                                if (!empty($user_key_data['feeds_count'])) {
                                    $query->where('value', '>', $user_key_data['feeds_count']);
                                }
                            })
                        ->where('key', 'feeds_count')
                        ->count();
            $post_rank += 1;

            $check_rank = UserDatas::where(function ($query) use ($user_key_data){
                                if (!empty($user_key_data['check_totalnum'])) {
                                    $query->where('value', '>', $user_key_data['check_totalnum']);
                                }
                            })
                        ->where('key', 'check_totalnum')
                        ->count();
            $check_rank += 1;
            
            $score_rank = CreditUser::where(function ($query) use ($user_credit){
                                if (!empty($user_credit->score)) {
                                    $query->where('score', '>', $user_credit->score);
                                }
                            })
                        ->count();
            $score_rank += 1;
            
            $user_rank_list = [
                'user_followed_rank' => $followed_rank,
                'user_post_rank' => $post_rank,
                'user_check_rank' => $check_rank,
                'user_score_rank' => $score_rank
            ];

            Cache::put($user_rank_key, $user_rank_list, 600);
        // }
        $res_list = array_merge($rank_list, $user_rank_list);

        return $res_list;
    }


    public function _parseRankList($list)
    {
        $resList = [];
        /*粉丝排行*/
        $follower['userrank'] = $list['user_followed_rank'];
        $followerlist = [];
        foreach ($list['followedrank'] as $fk => $fv) {
            $fv->info = $this->formatUserDatas($fv);
            $fv->rank = $fk+1;
            if ($fk < 10) {
                $followerlist[1][] = $fv;
            } else {
                $fnum = floor($fk / 10);
                $followerlist[$fnum + 1][] = $fv;
            }
        }
        $follower['ranknum'] = ceil(count($list['followedrank']) / 10);
        $follower['firstrank'] = $follower['ranknum'] ? 1 : 0;
        $follower['list'] = $followerlist;
        $resList['follower'] = $follower;
        /*积分排行榜*/
        $credit['userrank'] = $list['user_score_rank'];
        $creditlist = [];
        foreach ($list['creditrank'] as $ck => $cv) {
            $cv->info = $this->formatUserDatas($cv);
            $cv->rank = $ck+1;
            if ($ck < 10) {
                $creditlist[1][] = $cv;
            } else {
                $fnum = floor($ck / 10);
                $creditlist[$fnum +1][] = $cv;
            }
        }
        $credit['ranknum'] = ceil(count($list['creditrank']) / 10);
        $credit['firstrank'] = $credit['ranknum'] ? 1 : 0;
        $credit['list'] = $creditlist;
        $resList['credit'] = $credit;
        /*发布内容排行榜*/
        $post['userrank'] = $list['user_post_rank'];
        $postlist = [];
        foreach ($list['postrank'] as $pk => $pv) {
            $pv->info = $this->formatUserDatas($pv);
            $pv->rank = $pk+1;
            if ($pk < 10) {
                $postlist[1][] = $pv;
            } else {
                $fnum = floor($pk / 10);
                $postlist[$fnum +1][] = $pv;
            }
            
        }
        $post['ranknum'] = ceil(count($list['postrank']) / 10);
        $post['firstrank'] = $post['ranknum'] ? 1: 0;
        $post['list'] = $postlist;
        $resList['post'] = $post;
        /*累计签到排行榜*/
        $checktotal['userrank'] = $list['user_check_rank'];
        $checktotallist = [];
        
        foreach ($list['checktotalrank'] as $ctk => $ctv) {
            $ctv->info = $this->formatUserDatas($ctv);
            $ctv->rank = $ctk+1;
            if ($ctk < 10) {
                $checktotallist[1][] = $ctv;
            } else {
                //舍去法取整
                $fnum = floor($ctk / 10);
                $checktotallist[$fnum + 1][] = $ctv;
            }
        }
        //进一法取整
        $checktotal['ranknum'] = ceil(count($list['checktotalrank']) / 10);
        $checktotal['firstrank'] = $checktotal['ranknum'] ? 1 : 0;
        $checktotal['list'] = $checktotallist;
        $resList['checktotal'] = $checktotal;

        return $resList;
    }
}
