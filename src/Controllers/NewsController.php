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

        // 获取资讯列表
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
        return view('pcview::news.read', $data, $this->PlusData);
    }

    /**
     * 文章投稿页面
     */
    public function release(Request $request, int $news_id = 0)
    {
        // 资讯分类
        $cates = createRequest('GET', '/api/v2/news/cates');
        $data['cates'] = array_merge($cates['my_cates'], $cates['more_cates']);

        $data['news_id'] = $news_id;

        return view('pcview::news.release', $data, $this->PlusData);
    }
}
