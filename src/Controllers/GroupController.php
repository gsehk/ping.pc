<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class GroupController extends BaseController
{

    public function index(Request $request)
    {
        return view('pcview::group.index', [], $this->PlusData);
    }

    public function list(Request $request)
    {
        $user = $this->PlusData['TS']['id'] ?? 0;
        $type = $request->input('type');
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $groups = createRequest('GET', '/api/v2/groups', $params);

        if ($type == 2) {
            $groups = createRequest('GET', '/api/v2/groups/joined', $params);
        }

        $groups->map(function($group) use($user) {
            $has_join = array_where($group->members->toArray(), function ($value, $key) use ($user) {
                    return $value['user_id'] === $user;
            });
            $group->has_join = (bool) $has_join;
        });

        $group = clone $groups;
        $after = $group->pop()->id ?? 0;
        $data['group'] = $groups;
        $groupData = view('pcview::templates.group', $data, $this->PlusData)->render();

        return response()->json([
                'status'  => true,
                'data' => $groupData,
                'after' => $after
        ]);

    }

    public function read(Request $request)
    {
        return view('pcview::group.read', [], $this->PlusData);
    }
}