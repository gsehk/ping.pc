<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class MessageController extends BaseController
{
	public function index(Request $request, $type)
	{
		$data['type'] = $type;

		return view('pcview::template.message', $data, $this->PlusData);
	}

	public function pl(Request $request, Comment $model)
	{
        $user = $request->user();
        $limit = $request->query('limit', 1);
        $after = (int) $request->query('after', 0);

        $comments = $model->getConnection()->transaction(function () use ($user, $limit, $after, $model) {
            return $model->where(function ($query) use ($user) {
                return $query->where('target_user', $user->id)
                    ->orWhere('reply_user', $user->id);
            })
            ->where('user_id', '!=', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);
        });
        $data['list'] = $comments->map(function ($data) {
            $data->info = $this->formatUserDatas($data->user);
            return $this->formmatOldDate($data);
        });        

        $data['type'] = 'pl';
        $data['page'] = $comments->appends(['type'=>'pl'])->links('pcview::template.page');
        $html = view('pcview::template.message-body', $data, $this->PlusData)->render();        

        echo json_encode($html);
	}

	public function zan(Request $request)
	{
        $limit = $request->query('limit', 1);
        $after = $request->query('after', false);
        $user = $request->user();        

        $likes = LikeModel::with('likeable')
            ->where('target_user', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->paginate($limit);      

        $data['list'] = $likes->map(function ($data) {
        	$data->info = $this->formatUserDatas($data->user);
			return $data;
        });

        $data['type'] = 'zan';
        $data['page'] = $likes->appends(['type'=>'zan'])->links('pcview::template.page');
        $html = view('pcview::template.message-body', $data, $this->PlusData)->render();

        echo json_encode($html);
	}

    public function tz(Request $request)
    {
        $app = app(\Zhiyi\Plus\Http\Controllers\APIs\V1\UserController::class)->flushMessages($request);
        if ($app->original['status'] === true) {
            $data = $app->original['data'];
            foreach ($data as $key => $value) {
                # code...
            }
            echo json_encode($data);
        }
    }

	public function getMessageBody(Request $request, Comment $model, $type)
	{
		switch ($type) {
			case 'pl':
                $this->pl($request, $model);
				break;
			case 'zan':
				$this->zan($request);
				break;
			case 'tz':
				$this->tz($request);
				break;
            case 'at':
                # code...
                break;            
		}
	}

	    // 解析组装数据以兼容v1接口字段返回
    protected function formmatOldDate(Comment $data)
    {
        $arr = [
            'id' => $data->id,
            'user' => $data->info,
            'user_id' => $data->user_id,
            'to_user_id' => $data->target_user,
            'reply_to_user_id' => $data->reply_user,
            'comment_id' => $data->target,
            'comment_content' => $data->comment_content,
            'source_cover' => $data->target_image,
            'source_content' => $data->target_title,
            'source_id' => $data->target_id,
            'created_at' => $this->getTime($data->created_at),
        ];

        switch ($data->channel) {
            case 'feed':
	            $feed = Feed::where('id', $arr['source_id'])
	            	->with(['images' => function($query){
	            		$query->take(1);
	            	}])->first();
            	$feed->images && $arr['source_img'] = $feed->images[0]->id.'?w=80&h=80';
            	$arr['source_url'] = '/home/'.$arr['source_id'].'/feed';
                $arr['component'] = 'feed';
                $arr['comment_table'] = 'feed_comments';
                $arr['source_table'] = 'feeds';
                break;
            case 'news':
                $arr['component'] = 'news';
                $arr['comment_table'] = 'news_comments';
                $arr['source_table'] = 'news';
                break;
        }

        return $arr;
    }


	public function at(){}

}
