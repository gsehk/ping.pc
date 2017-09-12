<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models\Navigation;

class ConfigController extends Controller
{

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

    public function getnav(Request $request, int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {

            return response()->json(['data' => $nav]);
        }
    }

    public function delete(Request $request, int $nid)
    {
        $nav = Navigation::find($nid);
        if ($nav) {
            $nav->delete();
        }

        return response()->json(['message' => '删除成功']);
    }
}