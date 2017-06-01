<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\StorageTask;
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
    public function index(Request $request)
    {
        $datas['cid'] = $request->input('cid') ?: 1;
        /*  幻灯片  */
        $datas['silid'] = NewsRecommend::with('cover')->get();

        $datas['ischeck'] = CheckInfo::where('created_at', '>', Carbon::today())
            ->where(function($query){
                if ($this->mergeData) {
                    $query->where('user_id', $this->mergeData['TS']['id']);
                }
            })->orderBy('created_at', 'desc')->first();
        $datas['checkin'] = CheckInfo::where(function($query){
            if ($this->mergeData) {
                $query->where('user_id', $this->mergeData['TS']['id']);
            }
            })->first();

        /* 推荐文章第一条 */
        $datas['recommend'] = [];
        $rec = News::where('is_recommend', 1)
            ->orderBy('news.id', 'desc')
            ->take(6)
            ->select('id','title','updated_at','storage','from','author')
            ->with('storage')
            ->with('user.datas')
            ->get();
        if (count($rec)) {
            $rec[0]['info'] = $this->formatUserDatas($rec[0]['user']);
            unset($rec[0]['user']);
            $datas['recommend'] = $rec;
        }
        
        /*  文章分类  */
        $datas['cate'] = NewsCate::orderBy('rank', 'desc')->select('id','name')->get()->toArray();

        /*  热门作者 */
        $datas['author'] = [];
        $author = News::byAudit()
            ->orderBy('hits', 'desc')
            ->select('author')
            ->groupBy('author')
            ->take(3)
            ->with('user.datas')
            ->get();
        if (count($author)) {
            foreach ($author as $k => $v) {
                $v['info'] = $this->formatUserDatas($v->user);
                unset($v['user']);
            }
            $datas['author'] = $author;
        }

        return view('pcview::information.index', $datas, $this->mergeData);
    }

    public function read(int $news_id)
    {
        $uid = $this->mergeData['TS']['id'] ?? 0;
        $data = News::byAudit()
                ->where('id', $news_id)
                ->withCount('newsCount') //当前作者文章数
                ->with('user', 'link')
                ->with(['collection' => function( $query ){
                    return $query->count();
                }])->first();
        if (!$data) {
            return redirect( route('pc:news'), 302);
        }
        $data['is_digg_news'] = $uid ? NewsDigg::where('news_id', $news_id)->where('user_id', $uid)->count() : 0;
        $data['is_collect_news'] = $uid ? NewsCollection::where('news_id', $news_id)->where('user_id', $uid)->count() : 0;
        $user = $this->formatUserDatas($data->user);
        unset($data['user']);
        $data['user'] = $user;

        $data['hots'] = News::byAudit()
                        ->join('news_cates_links', 'news.id', '=', 'news_cates_links.news_id')
                        ->where([['news.author','=',$data->author], ['news_cates_links.cate_id','=',1]])
                        ->count();

        $data['news'] = News::byAudit()
                        ->where('author', $data->author)
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->select('id','title')
                        ->get()
                        ->toArray();
        News::where('id', $news_id)->increment('hits');

        // dd($data);exit;
        return view('pcview::information.read', $data, $this->mergeData);
    }

    public function release(Request $request)
    {
        $user_id = $this->mergeData['TS']['id'] ?? 0;
        
        $draft = News::where('audit_status', 2)->where('author', $user_id)->get();
        if ($request->id) {
            $data = $draft->where('id', $request->id)->first();
        }else{
            $data = [];
        }
        $data['count'] = count($draft);
        $data['cate'] = NewsCate::orderBy('rank', 'desc')->select('id','name')->get();

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

        $datas = News::Join('news_cates_links', function ($query) use ($cate_id) {
                    $query->on('news.id', '=', 'news_cates_links.news_id')->where('news_cates_links.cate_id', $cate_id);
                })
                ->where(function ($query) use ($max_id) {
                    if ($max_id > 0) {
                        $query->where('news.id', '<', $max_id);
                    }
                })
                ->byAudit()
                ->orderBy('news.id', 'desc')
                ->select('news.id','news.title','news.subject','news.updated_at','news.storage','news.comment_count','news.from')
                // ->with('storage')
                ->withCount('collection')
                ->get()->toArray();

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
                        ->select('id','title','updated_at','storage','content','from')
                        ->with('storage')
                        ->get();

                break;
            case 2:
                $stime = Carbon::create(null, null, 01);// 本月开始时间
                $etime = Carbon::create(null, null, $time->daysInMonth); // 本月结束时间

                $datas = News::whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                        ->orderBy('news.hits', 'desc')
                        ->take($limit)
                        ->select('id','title','updated_at','storage','content','from')
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
                        ->select('id','title','updated_at','storage','content','from')
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

    public function doSavePost(Request $request)
    {
        $type = $request->type; // 1 待审核 2 草稿
        /*if (!$request->cate_id) {
            return response()->json([
                'status' => false,
                'message' => '请选择文章分类',
            ])->setStatusCode(201);
        }*/

        if (!$request->task_id) {
            return response()->json([
                'status' => false,
                'message' => '没有上传封面图片',
            ])->setStatusCode(201);
        }
        if (mb_strlen($request->content, 'utf8') > 5000) {
            return response()->json([
                'status' => false,
                'message' => '内容不能大于5000字',
            ])->setStatusCode(201);
        }

        if ($request->news_id) {
            
            $news = News::find($request->news_id);
            if ($news) {
                $news->title = $request->title;
                $news->subject = $request->subject ?: getShort($request->content, 60);
                $news->author = $this->mergeData['TS']['id'] ?? 0;
                $news->content = $request->content;
                $news->storage = $request->task_id;
                $news->from = $request->source ?: '';
                $news->audit_status = $type;
                $news->save(); 
            }

        } else {
            $storage = StorageTask::where('id', $request->task_id)
                    ->select('hash')
                    ->with('storage')
                    ->first();
            if ($storage) {
                $storage_id = $storage['storage']['id'];
            }

            $news = new News();
            $news->title = $request->title;
            $news->subject = $request->subject ?: getShort($request->content, 60);
            $news->author = $this->mergeData['TS']['id'] ?? 0;
            $news->content = $request->content;
            $news->storage = $storage_id ?: '';
            $news->from = $request->source ?: '';
            $news->audit_status = $type; 
            $news->save();
        }
        

        /*$news_link = new NewsCateLink();
        $news_link->news_id = $news->id;
        $news_link->cate_id = $request->cate_id;
        $news_link->save();*/

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => $type == 1 ? '发布成功，请等待审核' : '保存成功',
            'data'    => ['url' => ($type == 1) ? route('pc:news') : route('pc:article', ['type' => 2]),'id' => $news->id]
        ]))->setStatusCode(200);
    }

    /**
    * 获取热门作者
    * @param  uid  [获取某个作者最新的文章]
    */
    public function getAuthorHot(Request $request)
    {
        $uid = $request->uid;
        $limit = $request->limit ?? 3;
        if ($uid) {
            // 获取uid对应的作者文章
            $datas = News::where('author', $uid)
                    ->orderBy('created_at', 'desc')
                    ->take($limit)
                    ->select('id','title')
                    ->get()
                    ->toArray();
        } else {
            //获取热门作者
            $datas = News::byAudit()
                    ->orderBy('hits', 'desc')
                    ->take($limit)
                    ->orderBy('author')
                    ->select('author')
                    ->with('user')
                    ->get();
        }

        return response()->json(static::createJsonData([
            'status'  => true,
            'code'    => 0,
            'message' => '获取成功',
            'data'    => $datas
        ]))->setStatusCode(200);
    }

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
