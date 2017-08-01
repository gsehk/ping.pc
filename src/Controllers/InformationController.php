<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\CheckInfo;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCateLink;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsRecommend;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCollection;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsComment;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getShort;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class InformationController extends BaseController
{
    /**
     * 文章首页
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        $datas['cid'] = $request->input('cid') ?: 1;
        $datas['slide'] = NewsRecommend::with('cover')->get();
        $datas['cate'] = NewsCate::get();
        $datas['hots'] = [
            'week' => $this->getRecentHot(1),
            'month' => $this->getRecentHot(2),
            'quarter' => $this->getRecentHot(3),
        ];        
        $user_id = $this->PlusData['TS']['id'] ?? 0;

        $datas['recommend'] = News::byAudit()
                ->select('id','title','updated_at','storage','from','author')
                ->where('is_recommend', 1)
                ->orderBy('id', 'desc')->take(6)
                ->get();
        $datas['author'] = News::byAudit()
                ->select('author')
                ->orderBy('hits', 'desc')
                ->groupBy('author')
                ->with('user')
                ->take(3)
                ->get();

        return view('pcview::information.index', $datas, $this->PlusData);
    }

    /**
     * 文章详情页
     */
    public function read(News $model, int $news_id)
    {
        $news = $model->byAudit()->where('id', $news_id)->first();
        if (!$news) {
            return redirect( route('pc:news'), 302);
        }
        $news->increment('hits');
        $user = $this->PlusData['TS']['id'] ?? 0;
        $news->news_count = $model->byAudit()->where('user_id', $news->user_id)->count();
        $news->hots_count = $model->byAudit()->where('user_id', $news->user_id)->where('cate_id', 1)->count();
        $news->like_count = $news->collection->count();
        $news->collect_count = $news->collection->count();
        $news->has_collect = $user ? $news->collection->where('user_id', $user)->count() : 0;
        $news->has_like = $news->liked($user);
        $news->user = $news->user;
        $news->hots = [
            'week' => $this->getRecentHot(1),
            'month' => $this->getRecentHot(2),
            'quarter' => $this->getRecentHot(3),
        ];
        $news->list = $model->byAudit()
                ->where('user_id', $news->user_id)
                ->select('id', 'title')
                ->take(4)->get();

        return view('pcview::information.read', $news, $this->PlusData);
    }

    /**
     * 文章投稿页面
     */
    public function release(Request $request, int $news_id = null)
    {
        $user_id = $this->PlusData['TS']['id'] ?? 0;
        $draft = News::where('audit_status', 2)->where('author', $user_id)->get();
        if ($news_id) {
            $data = $draft->where('id', $news_id)->first();
        }
        $data['user_id'] = $user_id;
        $data['count'] = $draft->count();
        $data['cate'] = NewsCate::get();

        return view('pcview::information.release', $data, $this->PlusData);
    }


    /**
     * 获取文章评论列表
     * 
     * @param  int  $news_id 文章id
     * @return mixed
     */
    public function commnets(Request $request, int $news_id)
    {
        $comments = createRequest('GET', '/api/v2/news/'.$news_id.'/comments');
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
     * 资讯列表.
     * 
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function lists(Request $request)
    {
        $cate_id = $request->cate_id;
        $max_id = $request->after;
        $limit = $request->limit ?? 15;

        if ($cate_id) {
            $datas = News::byAudit()
                ->Join('news_cates_links', function ($query) use ($cate_id) {
                    $query->on('news.id', '=', 'news_cates_links.news_id')->where('news_cates_links.cate_id', $cate_id);
                })
                ->where(function ($query) use ($max_id) {
                    if ($max_id > 0) {
                        $query->where('news.id', '<', $max_id);
                    }
                })
                ->orderBy('news.id', 'desc')
                ->select('news.id','news.title','news.subject','news.created_at','news.storage','news.comment_count','news.from')
                ->withCount('collection')
                ->take($limit)
                ->get();
        } else {
            $datas = News::byAudit()
                ->where(function ($query) use ($max_id) {
                    if ($max_id > 0) {
                        $query->where('id', '<', $max_id);
                    }
                })
                ->orderBy('id', 'desc')
                ->select('id','title','subject','created_at','storage','comment_count','from')
                ->withCount('collection')
                ->take($limit)
                ->get();
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
    public function getRecentHot($type = 1, $limit = 5)
    {
        $time = Carbon::now();
        switch ($type) {
            case 1:
                // $stime = Carbon::createFromTimestamp($time->timestamp - $time->dayOfWeek*60*60*24);// 本周开始时间
                // $etime = Carbon::createFromTimestamp($time->timestamp + (6-$time->dayOfWeek)*60*60*24); // 本周结束时间

                // $datas = News::whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                $stime = $time->subDays(7)->toDateTimeString();
                $datas = News::byAudit()
                        ->where('updated_at', '>', $stime)
                        ->select('id','title', 'hits')
                        ->orderBy('hits', 'desc')
                        ->take($limit)
                        ->get();
                break;
            case 2:
                $stime = Carbon::create(null, null, 01);// 本月开始时间
                $etime = Carbon::create(null, null, $time->daysInMonth); // 本月结束时间

                $datas = News::byAudit()
                        ->whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->select('id','title','hits')
                        ->orderBy('hits', 'desc')
                        ->take($limit)
                        ->get();
                break;
            case 3:
                $season = ceil($time->month/3);//当月是第几季度
                $stime = Carbon::create($time->year, $season*3-3+1, 01, 0, 0, 0);// 本季度开始时间
                $etime = Carbon::create($time->year, $season*3, $time->daysInMonth, 23, 59, 59); // 本季度结束时间

                $datas = News::byAudit()
                        ->whereBetween('updated_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->select('id','title','hits')
                        ->orderBy('hits', 'desc')
                        ->take($limit)
                        ->get();
                break;
        }

        return $datas;
    }

    /**
     * 添加/修改 投稿
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function doSavePost(Request $request)
    {
        $type = $request->type; // 1 待审核 2 草稿
        if (!$request->storage_id) {
            return response()->json([
                'status' => false,
                'message' => '没有上传封面图片',
            ]);
        }
        if (mb_strlen($request->content, 'utf8') > 5000) {
            return response()->json([
                'status' => false,
                'message' => '内容不能大于5000字',
            ]);
        }

        $cate_ids = trim($request->cate_ids, '|');
        if ($request->news_id) {
            $news = News::find($request->news_id);
            if ($news) {
                $news->title = $request->title;
                $news->subject = $request->subject ?: getShort($request->content, 60);
                $news->author = $this->PlusData['TS']['id'] ?? 0;
                $news->content = $request->content;
                $news->storage = $request->storage_id;
                $news->from = $request->source ?: '';
                $news->audit_status = $type;
                $news->save();
                if ($cate_ids) {
                    $cates = [];
                    $cate_ids = explode('|', $cate_ids);
                    foreach ($cate_ids as $k => $v) {
                        $cates[$k]['news_id'] = $news->id;
                        $cates[$k]['cate_id'] = $v;
                    }
                    DB::transaction(function () use ($news, $cates) {
                        NewsCateLink::where('news_id', $news->id)->delete();
                        NewsCateLink::insert($cates);
                    });
                }
            }

        } else {
            $news = new News();
            $news->title = $request->title;
            $news->subject = $request->subject ?: getShort($request->content, 60);
            $news->author = $this->PlusData['TS']['id'] ?? 0;
            $news->content = $request->content;
            $news->storage = $request->storage_id;
            $news->from = $request->source ?: '';
            $news->audit_status = $type; 
            $news->save();
            if ($cate_ids) {
                $cates = [];
                $cate_ids = explode('|', $cate_ids);
                foreach ($cate_ids as $k => $v) {
                    $cates[$k]['news_id'] = $news->id;
                    $cates[$k]['cate_id'] = $v;
                }
                NewsCateLink::insert($cates);
             }
            
        }
        if ($request->storage_id) {
            $fileWith = FileWith::find($request->storage_id);
            if ($fileWith) {
                $fileWith->channel = 'news:storage';
                $fileWith->raw = $news->id;
                $fileWith->save();
            }
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => $type == 1 ? '发布成功，请等待审核' : '保存成功',
            'data'    => [
                'url' => ($type == 1) 
                ? route('pc:article', ['user_id'=>$this->PlusData['TS']['id'],'type' => 1]) 
                : route('pc:article', ['user_id'=>$this->PlusData['TS']['id'],'type' => 2])
                ,'id' => $news->id
            ]
        ]))->setStatusCode(200);
    }

}
