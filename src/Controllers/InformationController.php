<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsRecommend;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\view;

class InformationController extends BaseController
{
    public function index(Request $request)
    {
        $datas['cid'] = $request->input('cid') ?: 1;
        /*  幻灯片  */
        $datas['silid'] = $this->getRecommendList();

        /*  文章分类  */
        $datas['cate'] = NewsCate::orderBy('rank', 'desc')->select('id','name')->get()->toArray();

        return view('information.index', $datas);
    }

    public function read(int $news_id)
    {
        $data = News::where('id', $news_id)->first()->toArray();

        return view('information.read', $data);
    }

    public function getRecommendList(int $cate_id = 0)
    {
        $data = NewsRecommend::where('cate_id', $cate_id)->with('cover')->get()->toArray();

        return $data;
    }

    public function release()
    {

        return view('information.release');
    }

    /**
     * 资讯列表.
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function getNewsList(Request $request)
    {
        $cate_id = $request->cate_id;
        $max_id = $request->max_id;
        $limit = $request->limit ?? 15;

        switch ($cate_id) {
            // 推荐
            case -1:
                $datas['list'] = News::where('is_recommend', 1)
                        ->where(function ($query) use ($max_id) {
                            if ($max_id > 0) {
                                $query->where('news.id', '<', $max_id);
                            }
                        })
                        ->orderBy('news.id', 'desc')
                        ->take($limit)
                        ->select('id','title','updated_at','storage','from')
                        ->with('storage')
                        ->get()->toArray();
                break;

            // 分类
            default:
                $datas = News::Join('news_cates_links', function ($query) use ($cate_id) {
                            $query->on('news.id', '=', 'news_cates_links.news_id')->where('news_cates_links.cate_id', $cate_id);
                        })
                        ->where(function ($query) use ($max_id) {
                            if ($max_id > 0) {
                                $query->where('news.id', '<', $max_id);
                            }
                        })
                        ->orderBy('news.id', 'desc')
                        ->select('news.id','news.title','news.updated_at','news.storage','news.comment_count','news.from')
                        // ->with('storage')
                        ->with(['collection'=>function($query){
                            $query->count();
                        }])->get()->toArray();
                break;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
    }

    /**
     * 获取近期资讯列表
     * 
     * @date   2017-04-28
     * @param  type     [分类id]
     * @return mixed  返回结果
     */
   public function getRecentHot(Request $request)
   {
        $time = Carbon::now();
        $type = $request->type;
        $limit = $request->limit ?? 5;
        
        switch ($type) {
            case 1:
                $stime = Carbon::createFromTimestamp($time->timestamp - $time->dayOfWeek*60*60*24);// 本周开始时间
                $etime = Carbon::createFromTimestamp($time->timestamp + (6-$time->dayOfWeek)*60*60*24); // 本周结束时间

                $datas = News::whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title','updated_at','storage','from')
                        ->with('storage')
                        ->get();

                break;
            case 2:
                $stime = Carbon::create(null, null, 01);// 本月开始时间
                $etime = Carbon::create(null, null, $time->daysInMonth); // 本月结束时间

                $datas = News::whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title','updated_at','storage','from')
                        ->with('storage')
                        ->get();

                break;
            case 3:
                $season = ceil($time->month/3);//当月是第几季度
                $stime = Carbon::create($time->year, $season*3-3+1, 01, 0, 0, 0);// 本季度开始时间
                $etime = Carbon::create($time->year, $season*3, $time->daysInMonth, 23, 59, 59); // 本季度结束时间

                $datas = News::whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title','updated_at','storage','from')
                        ->with('storage')
                        ->get();

                break;
            default:
                # code...
                break;
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
   }

}
