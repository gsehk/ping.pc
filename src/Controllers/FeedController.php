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

    public function read(Request $request, News $model, int $feed_id)
    {
        $feed = createRequest('GET', '/api/v2/feeds/'.$feed_id);
        $feed->collect_count = $feed->collection->count();
        $data['feed'] = $feed;
        $data['user'] = $feed->user;

        $feed->byFeedId($feed_id)->increment('feed_view_count');
        // dd($data);
        return view('pcview::feed.read',$data, $this->PlusData);
    }

    /**
     * 动态评论
     * 
     * @return mixed
     */
    public function comments(Request $request, int $feed_id)
    {
        $comments = createRequest('GET', '/api/v2/feeds/'.$feed_id.'/comments');
        $comment = clone $comments['comments'];
        $comment->map(function($comment){
            return [
                'user' => $comment->user,
            ];
        });
        $after = $comment->pop()->id ?? 0;        

        return response()->json([
            'status'  => true,
            'after' => $after,
            'data' => $comments['comments'],
        ]);
    }

    /**
     * 动态收藏
     * 
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function collection(Request $request)
    {
        $collects = createRequest('GET', '/api/v2/feeds/collections');
        $collects->map(function($collect){
            return [
                'user' => $collect->user,
            ];
        });
        $collect = clone $collects;
        $datas['feeds'] = $collects;
        $after = $collect->pop()->id ?? 0;
        $html = view('pcview::templates.feeds', $datas, $this->PlusData)->render();
        
        return response()->json([
            'status' => true,
            'after' => $after,
            'data' => $html,
        ]);
    }

    /**
     * 文章收藏
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection $collect
     * @return mixed
     */
    public function newsCollect(Request $request, News $NewsModel, NewsCollection $collect)
    {
        $user_id = $request->user()->id ?? 0;
        $after = $request->after;
        $news = $NewsModel->whereIn('id', $collect->pluck('news_id'))
            ->where(function ($query) use ($after) {
                if ($after > 0) {
                    $query->where('id', '<', $after);
                }
            })->orderBy('id', 'desc')->get();

        $news->map(function($news) use ($user_id, $collect) {
            $news->images = $news->images;
            $news->comments = $news->comments;
            $news->collect_count = $news->collection->count();
            $news->has_collect = $user_id ? $collect->where('news_id', $news->id)->count() : 0;
            unset($news->collection);

            return $news;
        });
        $new = clone $news;
        $datas['data'] = $news;
        $after = $new->pop()->id ?? 0;
        $html = view('pcview::templates.profile-collect', $datas, $this->PlusData)->render();

        return response()->json(static::createJsonData([
            'status' => true,
            'after' => $after,
            'data' => $html
        ]));        

    }


    /**
     * 获取近期资讯列表
     * 
     * @date   2017-04-28
     * @param  type     [分类id]
     * @return mixed  返回结果
     */
    public function getRecentHot($type = 1, $limit = 5)
    {
        $time = Carbon::now();
        switch ($type) {
            case 1:
                // $stime = Carbon::createFromTimestamp($time->timestamp - $time->dayOfWeek*60*60*24);// 本周开始时间
                // $etime = Carbon::createFromTimestamp($time->timestamp + (6-$time->dayOfWeek)*60*60*24); // 本周结束时间
                // $datas = News::whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                $stime = $time->subDays(7)->toDateTimeString();
                $datas = News::where('updated_at', '>', $stime)
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title')
                        ->get();
                break;
            case 2:
                $stime = Carbon::create(null, null, 01);// 本月开始时间
                $etime = Carbon::create(null, null, $time->daysInMonth); // 本月结束时间

                $datas = News::whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title')
                        ->get();
                break;
            case 3:
                $season = ceil($time->month/3);//当月是第几季度
                $stime = Carbon::create($time->year, $season*3-3+1, 01, 0, 0, 0);// 本季度开始时间
                $etime = Carbon::create($time->year, $season*3, $time->daysInMonth, 23, 59, 59); // 本季度结束时间

                $datas = News::whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title')
                        ->get();
                break;
        }

        return $datas;
    }
    
    /**
     * 签到
     * 
     * @return mixed
     */
    public function checkin()
    {
        $user_id = $this->PlusData['TS']['id'] ?? 0;

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
                $credit_user->con_num = $data['con_num'];
                // 更新连续签到和累计签到的数据
                $totalnum = UserDatas::where('user_id', $user_id)
                            ->where('key', 'check_totalnum')
                            ->first();
                if ($totalnum) {
                    // 更新总签到数
                    $totalnum->value = $data['total_num'];
                    $totalnum->save();

                    // 更新连续签到数
                    UserDatas::where('user_id', $user_id)
                        ->where('key', 'check_connum')
                        ->update([
                            'value' => $data['con_num']
                        ]);
                } else {
                    // 第一次写入
                    UserDatas::create([
                        'user_id' => $user_id,
                        'key' => 'check_totalnum',
                        'value' => $data['total_num'],
                    ]);
                    UserDatas::create([
                        'user_id' => $user_id,
                        'key' => 'check_connum',
                        'value' => $data['con_num'],
                    ]);
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
                $change = '<font color="lightskyblue">+'.$credit_ruls->score.'</font>';
            } else {
                $change = '<font color="red">-'.$credit_ruls->score.'</font>';
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
