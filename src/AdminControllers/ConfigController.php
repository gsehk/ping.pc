<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;

class ConfigController extends Controller
{

    /**
     * 导航配置列表.
     *
     * @param  Request     $request
     * @param  int|integer $pos  0-顶部 1-底部
     * @return mixed
     */
    public function index(Request $request, int $pos = 0)
    {
        $data = [];
        $parents = [];
        $subsets = [];
        $nav = Navigation::byPos($pos)->get();
        foreach ($nav as $item) {
            if($item->parent_id <= 0){
                $parents[] = $item;
            } else {
                $subsets[$item->parent_id][] = $item;
            }
        }
        foreach ($parents as $parent) {
            $data[] = $parent;
            if (array_key_exists($parent->id, $subsets)) {
                foreach ($subsets[$parent->id] as $subset) {
                    $data[] = $subset;
                }
            }
        }

        return response()->json([
            'status'  => true,
            'data' => $data,
        ])->setStatusCode(200);
    }

    /**
     * 添加编辑导航.
     *
     * @param  Request $request
     * @return mixed
     */
    public function manage(Request $request)
    {
        $nid = $request->input('id', 0);
        $nav = Navigation::find($nid);
        if ($nav) {
            $nav->url = $request->url;
            $nav->name = $request->name;
            $nav->app_name = $request->app_name;
            $nav->order_sort = $request->order_sort;
            $nav->parent_id = $request->parent_id;
            $nav->position = $request->position;
            $nav->status = $request->status;
            $nav->target = $request->target;
            $nav->save();
        } else {
            $nav = new Navigation();
            $nav->url = $request->url;
            $nav->name = $request->name;
            $nav->app_name = $request->app_name;
            $nav->order_sort = $request->order_sort;
            $nav->parent_id = $request->parent_id;
            $nav->position = $request->position;
            $nav->status = $request->status;
            $nav->target = $request->target;
            $nav->save();
        }

        /*Navigation::firstOrCreate([
            'id' => $nid,
            ], [
            'name' => $request->name,
            'app_name' => $request->app_name,
            'url' => $request->url,
            'target' => $request->target,
            'status' => $request->status,
            'position' => $request->position,
            'parent_id' => $request->parent_id,
            'order_sort' => $request->order_sort,
        ]);*/
        return response()->json([
            'status'  => true,
            'message' => '操作成功',
        ])->setStatusCode(200);
    }

    /**
     * 获取一条导航记录.
     *
     * @param  Request $request
     * @param  int     $nid  记录id
     * @return mixed
     */
    public function getnav(Request $request, int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {

            return response()->json(['data' => $nav]);
        }
    }

    /**
     * 删除导航记录.
     *
     * @param  Request $request
     * @param  int     $nid   记录id
     * @return mixed
     */
    public function delete(Request $request, int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {
            $nav->delete();
        }

        return response()->json(['message' => '删除成功']);
    }

    /**
     * 获取pc基础配置信息.
     *
     * @return mixed
     */
    public function get(Request $request, Repository $config, ResponseFactory $response)
    {
        return $response->json($config['pc'])->setStatusCode(200);
    }

    /**
     * 更新pc站基本配置信息.
     *
     * @return mixed
     */
    public function updateSiteInfo(Request $request, Configuration $config, ResponseFactory $response)
    {
        $config->set('pc', $request->input('site'));

        return $response->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }
}