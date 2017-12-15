<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use Illuminate\Http\Request;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class GroupController extends BaseController
{
    /**
     * 圈子首页
     * @author 28youth
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->PlusData['current'] = 'group';
        $data['cates'] = createRequest('GET', '/api/v2/plus-group/categories');

        return view('pcview::group.index', $data, $this->PlusData);
    }

    /**
     * 创建圈子.
     *
     * @author 28youth
     * @param Request $request
     */
    public function create(Request $request)
    {
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['cates'] = createRequest('GET', '/api/v2/plus-group/categories');

        return view('pcview::group.create', $data, $this->PlusData);
    }

    /**
     * 编辑圈子.
     *
     * @author 28youth
     * @param Request $request
     */
    public function manageGroup(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $data['tags'] = createRequest('GET', '/api/v2/tags');
        $data['cates'] = createRequest('GET', '/api/v2/plus-group/categories');
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);

        return view('pcview::group.manage_edit', $data, $this->PlusData);
    }

    /**
     * 成员管理.
     *
     * @author 28youth
     * @param Request $request
     */
    public function manageMember(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['members'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members', ['type'=>'member']);
        $data['manager'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members', ['type'=>'manager']);

        return view('pcview::group.manage_member', $data, $this->PlusData);
    }

    /**
     * 圈子资金管理.
     *
     * @author 28youth
     * @param Request $request
     */
    public function bankroll(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['bankroll'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/incomes');

        return view('pcview::group.manage_bankroll', $data, $this->PlusData);
    }

    /**
     * 圈子举报管理.
     *
     * @author 28youth
     * @param Request $request
     */
    public function report(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $params = [
            'limit' => $request->query('limit', 15),
            'offset' => $request->query('offset', 0),
            'group_id' => $request->query('group_id', 2),
        ];
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['reports'] = createRequest('GET', '/api/v2/plus-group/reports/', $params);

        return view('pcview::group.manage_report', $data, $this->PlusData);
    }

    /**
     * 举报详情.
     *
     * @author 28youth
     * @param Request $request
     */
    public function reportDetail(Request $request)
    {
        $group_id = $request->query('group_id');
        $params = [
            'limit' => $request->query('limit', 15),
            'offset' => $request->query('offset', 0),
            'group_id' => $request->query('group_id'),
        ];
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['reports'] = createRequest('GET', '/api/v2/plus-group/reports/', $params);

        return view('pcview::group.manage_report_detail', $data, $this->PlusData);
    }

    /**
     * 发布帖子.
     *
     * @author 28youth
     * @param  Request $request
     * @param  int     $group_id 圈子id
     */
    public function publish(Request $request, int $group_id)
    {
        $data['group_id'] = $group_id;

        return view('pcview::group.publish', $data, $this->PlusData);
    }

    /**
     * 阅读公告详情.
     *
     * @author 28youth
     * @param  Request $request
     */
    public function noticeRead(Request $request)
    {
        $group_id = $request->query('group_id');
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);

        return view('pcview::group.notice', $data, $this->PlusData);
    }

    /**
     * 圈子成员界面.
     *
     * @author 28youth
     * @param  Request $request
     */
    public function member(Request $request)
    {
        $group_id = $request->query('group_id');
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['members'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members', ['type'=>'member']);
        $data['manager'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members', ['type'=>'manager']);

        return view('pcview::group.member', $data, $this->PlusData);
    }

    /**
     * 圈子列表
     * @author 28youth
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $cate = $request->input('cate', 1);
        $params = [
            'offset' => $request->query('offset', 0),
            'limit' => $request->query('limit', 15),
            'category_id' => $request->query('category_id'),
        ];

        $groups = createRequest('GET', '/api/v2/plus-group/groups', $params);

        if ($cate == 2) {
            $params = [
                'offset' => $request->query('offset', 0),
                'limit' => $request->query('limit', 15),
            ];
            $groups = createRequest('GET', '/api/v2/plus-group/user-groups', $params);
        }

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

    /**
     * 成员列表
     * @author 28youth
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function memberList(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 15),
            'type' => $request->query('type', 'member'),
        ];
        $group = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $members = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members', $params);
        if ($params['type'] == 'audit') {

            $members = createRequest('GET', '/api/v2/plus-group/user-group-audit-members', $params);
        }

        $member = clone $members;
        $after = $member->pop()->id ?? 0;
        $data['type'] = $params['type'];
        $data['group'] = $group;
        $data['members'] = $members;
        $memberData = view('pcview::templates.member', $data, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $memberData,
            'after' => $after
        ]);
    }

    /**
     * 举报列表
     * @author 28youth
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportList(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $params = [
            'after' => $request->query('after', 0),
            'limit' => $request->query('limit', 15),
            'status' => $request->query('status'),
            'group_id' => $request->query('group_id'),
        ];
        $reports = createRequest('GET', '/api/v2/plus-group/reports', $params);

        $report = clone $reports;
        $after = $report->pop()->id ?? 0;
        $data['reports'] = $reports;
        $reportData = view('pcview::templates.report', $data, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'after' => $after,
            'data' => $reportData,
        ]);
    }

    /**
     * 圈子财务记录.
     *
     * @author 28youth
     * @param  Request $request
     */
    public function incomes(Request $request)
    {
        $group_id = $request->query('group_id', 2);
        $params = [
            'limit' => $request->query('limit', 15),
            'after' => $request->query('after', 0),
            'type' => $request->query('type', 'all'),
            'start' => strtotime($request->query('start')),
            'end' => strtotime($request->query('end')),
        ];
        $records = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/incomes', $params);
        $record = clone $records;
        $after = $record->pop()->id ?? 0;
        $data['record'] = $records;
        $data['type'] = $request->query('type');
        $data['loadcount'] = $request->query('loadcount');
        $recordData = view('pcview::templates.record', $data, $this->PlusData)->render();

        return response()->json([
            'status' => true,
            'data' => $recordData,
            'after' => $after
        ]);
    }

    /**
     * 圈子详情
     * @author ZsyD
     * @param Request $request
     * @param int $group_id [圈子id]
     * @return mixed
     */
    public function read(Request $request, int $group_id)
    {
        $this->PlusData['current'] = 'group';

        $data['type'] = $request->input('type', 'latest_post');
        $user = $this->PlusData['TS']['id'] ?? 0;
        $data['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $data['members'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/members',['type'=>'member']);

        return view('pcview::group.read', $data, $this->PlusData);
    }

    /**
     * 获取指定圈子动态
     * @author ZsyD
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLists(Request $request)
    {
        $group_id = $request->input('group_id');
        $type = $request->input('type');
        $params = [
            'offset' => $request->query('offset', 0),
            'limit' => $request->query('limit', 15),
        ];

        $posts = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/posts', $params);
        $after =  0;

        $posts['conw'] = 815;
        $posts['conh'] = 545;
        $posts['group'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id);
        $feedData = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $feedData,
            'after' => $after
        ]);
    }

    /**
     * 创建圈子动态后获取动态信息
     * @author ZsyD
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPost(Request $request)
    {
        $posts['posts'] = collect();
        $post = createRequest('GET', '/api/v2/groups/'.$request->group_id.'/posts/'.$request->post_id);
        $posts['posts']->push($post);
        $feedData = view('pcview::templates.group_posts', $posts, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $feedData
        ]);
    }

    /**
     * 圈子动态详情
     * @author ZsyD
     * @param  Request $request
     * @param  [type]  $group_id [圈子id]
     * @param  [type]  $post_id  [圈子动态id]
     * @return mixed
     */
    public function postDetail(Request $request, $group_id, $post_id)
    {
        $this->PlusData['current'] = 'group';

        $data['post'] = createRequest('GET', '/api/v2/plus-group/groups/'.$group_id.'/posts/'.$post_id);

        return view('pcview::group.post', $data, $this->PlusData);
    }

    /**
     * 圈子动态评论列表
     * @author ZsyD
     * @param  Request $request
     * @param  [type]  $group_id [圈子id]
     * @param  [type]  $post_id  [圈子动态id]
     * @return mixed
     */
    public function comments(Request $request, $post_id)
    {
        $params = [
            'after' => $request->query('after') ?: 0
        ];

        $comments = createRequest('GET', '/api/v2/plus-group/group-posts/'.$post_id.'/comments', $params);

        $comment = clone $comments['comments'];
        $after = $comment->pop()->id ?? 0;

        /*$comments['comments']->map(function($item){
            $item->user = $item->user;

            return $item;
        });*/
        $comments['top'] = false;
        $commentData = view('pcview::templates.comment', $comments, $this->PlusData)->render();

        return response()->json([
            'status'  => true,
            'data' => $commentData,
            'after' => $after
        ]);
    }
}