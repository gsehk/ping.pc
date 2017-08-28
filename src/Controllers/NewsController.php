<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getShort;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class NewsController extends BaseController
{
    /**
     * 文章首页
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request, int $cate_id = 0)
    {   
        // 获取资讯首页广告位ID
        $space = $this->PlusData['site']['ads'];

        // 顶部广告
        $data['ads']['top'] = createRequest('GET', '/api/v2/advertisingspace/' . $space['pc:news:top']['id'] . '/advertising')->pluck('data');
        
        // 右侧广告
        $data['ads']['right'] = createRequest('GET', '/api/v2/advertisingspace/' . $space['pc:news:right']['id'] . '/advertising')->pluck('data');
        
        // 资讯分类
        $cates = createRequest('GET', '/api/v2/news/cates');
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);

        $data['cate_id'] = $cate_id;

        return view('pcview::news.index', $data, $this->PlusData);
    }

    /**
     * 资讯列表.
     * 
     * @param  $cate_id [分类ID]
     * @return mixed 返回结果
     */
    public function list(Request $request)
    {
        $params = [
            'cate_id' => $request->query('cate_id'),
            'after' => $request->query('after') ?: 0
        ];

        $news['news'] = createRequest('GET', '/api/v2/news', $params);
        $new = clone $news['news'];
        $after = $new->pop()->id ?? 0;
        $newsData = view('pcview::templates.news', $news, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $newsData,
                'after' => $after
        ]);
    }

    /**
     * 文章详情页
     */
    public function read(int $news_id)
    {
        // 获取资讯详情
        $news = createRequest('GET', '/api/v2/news/' . $news_id);
        $news['collect_count'] = $news->with('collections')->count();

        $data['news'] = $news;


        // 获取资讯首页广告位ID
        $space = $this->PlusData['site']['ads'];

        // 右侧广告
        $data['ads']['right'] = createRequest('GET', '/api/v2/advertisingspace/' . $space['pc:news:right']['id'] . '/advertising')->pluck('data');
        

        return view('pcview::news.read', $data, $this->PlusData);
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
