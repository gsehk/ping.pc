<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

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
}
