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

        $datas['author'] = [];
        $datas['recommend'] = [];
        $datas['cid'] = $request->input('cid') ?: 0;
        $datas['slide'] = NewsRecommend::with('cover')->get();
        $datas['cate'] = NewsCate::orderBy('rank', 'desc')->select('id','name')->get()->toArray();
        $datas['hots'] = ['week' => $this->getRecentHot(1), 'month' => $this->getRecentHot(2), 'quarter' => $this->getRecentHot(3)];
        $user_id = $this->mergeData['TS']['id'] ?? 0;

        $recommend = News::byAudit()
                ->where('is_recommend', 1)
                ->orderBy('id', 'desc')->take(6)
                ->select('id','title','updated_at','storage','from','author')
                ->with('images', 'user.datas')
                ->get();
        if (!$recommend->isEmpty()) {
            $recommend->first()->info = $this->formatUserDatas($recommend->first()->user);
            $datas['recommend'] = $recommend;
        }

        $author = News::byAudit()
                ->orderBy('hits', 'desc')->select('author')
                ->groupBy('author')->take(3)->with('user.datas')
                ->get();
        if (!$author->isEmpty()) {
            foreach ($author as $k => $v) {
                $v->info = $this->formatUserDatas($v->user);
            }
            $datas['author'] = $author;
        }

        return view('pcview::information.index', $datas, $this->mergeData);
    }

    /**
     * 文章详情页
     * 
     * @param  int    $news_id [description]
     * @return [type]          [description]
     */
    public function read(int $news_id)
    {
        if (!$news_id) {
            return redirect( route('pc:news'), 302);
        }
        News::where('id', $news_id)->increment('hits');
        $uid = $this->mergeData['TS']['id'] ?? 0;
        $news = News::byAudit()->where('id', $news_id)
                ->withCount(['newsCount' => function($query){
                    $query->where('audit_status', 0);
                }])
                ->with('link')
                ->with(['collection' => function ($query) {
                    return $query->count();
                }])->first();
        if ($news->user) {
            $user = $this->formatUserDatas($news->user);
            unset($news->user);
            $news->user = $user;
        }
        $news['is_digg_news'] = $uid ? NewsDigg::where('news_id', $news_id)->where('user_id', $uid)->count() : 0;
        $news['is_collect_news'] = $uid ? NewsCollection::where('news_id', $news_id)->where('user_id', $uid)->count() : 0;
        $news['hots'] = ['week' => $this->getRecentHot(1), 'month' => $this->getRecentHot(2), 'quarter' => $this->getRecentHot(3)];
        $news['hotNum'] = News::byAudit()
                    ->join('news_cates_links', 'news.id', '=', 'news_cates_links.news_id')
                    ->where([['news.author','=',$news->author], ['news_cates_links.cate_id','=',1]])
                    ->count();
        $news['news'] = News::byAudit()->where('author', $news->author)
                    ->orderBy('created_at', 'desc')->take(4)
                    ->select('id','title')
                    ->get();

        return view('pcview::information.read', $news, $this->mergeData);
    }

    /**
     * 文章投稿页面
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function release(Request $request, int $news_id = null)
    {
        $data = [];
        $user_id = $this->mergeData['TS']['id'] ?? 0;
        $draft = News::where('audit_status', 2)->where('author', $user_id)->get();
        if ($news_id) {
            $data = $draft->where('id', $news_id)->first();
            $data['links'] = $data->links ?: '';
        }
        $data['user_id'] = $user_id;
        $data['count'] = $draft->count();
        $data['cate'] = NewsCate::where('id', '!=', 1)->orderBy('rank', 'desc')->select('id','name')->get();

        return view('pcview::information.release', $data, $this->mergeData);
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
        foreach ($datas as $value) {
            $value['_created_at'] = $this->getTime($value->created_at);
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
                $news->author = $this->mergeData['TS']['id'] ?? 0;
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
            $news->author = $this->mergeData['TS']['id'] ?? 0;
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
                ? route('pc:article', ['user_id'=>$this->mergeData['TS']['id'],'type' => 1]) 
                : route('pc:article', ['user_id'=>$this->mergeData['TS']['id'],'type' => 2])
                ,'id' => $news->id
            ]
        ]))->setStatusCode(200);
    }

    /**
     * 获取文章评论列表
     * 
     * @param  int     $news_id 文章id
     * @return [type]           [description]
     */
    public function getCommentList(Request $request, int $news_id)
    {
        $limit = $request->get('limit',15);
        $max_id = intval($request->get('max_id'));
        if(!$news_id) {
            return response()->json([
                'status' => false,
                'code' => 9001,
                'message' => '资讯ID不能为空'
            ])->setStatusCode(400);
        }
        $comments = NewsComment::byNewsId($news_id)
                ->take($limit)
                ->where(function($query) use ($max_id) {
                    if ($max_id > 0) {
                        $query->where('id', '<', $max_id);
                    }
                })
                ->select(['id', 'created_at', 'comment_content', 'user_id', 'news_id', 'reply_to_user_id','comment_mark'])
                ->with('user')
                ->orderBy('id','desc')
                ->get();
        foreach ($comments as $key => &$value) {
            $value['info'] = $this->formatUserDatas($value['user']);
            unset($value['user']);
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $comments,
        ]))->setStatusCode(200);
    }

}
