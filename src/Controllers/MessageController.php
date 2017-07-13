<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Controllers;

use DB;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class MessageController extends BaseController
{
	public function index(Request $request, $type)
	{
		$data['type'] = $type;

		return view('pcview::template.message', $data, $this->mergeData);
	}

	public function pl(Request $request)
	{
		$uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $comment = Comment::join('users', 'users.id', '=', 'comments.user_id')
        ->where(function ($query) use ($uid) {
            $query->where('comments.target_user', $uid)->orWhere('comments.reply_user', $uid);
        })
        ->where('comments.user_id', '!=', $uid)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('comments.id', '<', $max_id);
            }
        })
        ->with('user.datas')
        ->select('comments.*')
        ->paginate(1);
        
        $data['list'] = $comment->map(function ($data) {
        	$data->info = $this->formatUserDatas($data->user);
			return $this->formmatOldDate($data);
        });
        $data['type'] = 'pl';
        $data['page'] = $comment->appends(['type'=>'pl'])->links('pcview::template.ajaxpage');
		$html = view('pcview::template.message-body', $data, $this->mergeData)->render();

		echo json_encode($html);
	}

	public function zan(Request $request)
	{
		$uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = $request->input('max_id', 0);
        $digg = Digg::join('users', 'users.id', '=', 'diggs.user_id')
        ->where('diggs.to_user_id', $uid)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('diggs.id', '<', $max_id);
            }
        })
        ->with('user.datas')
        ->select('diggs.*')
        ->paginate(1);

        $data['list'] = $digg->map(function ($data) {
        	$data->info = $this->formatUserDatas($data->user);
			return $data;
        });
        $data['type'] = 'zan';
        $data['page'] = $digg->appends(['type'=>'zan'])->links('pcview::template.ajaxpage');
        $html = view('pcview::template.message-body', $data, $this->mergeData)->render();

        echo json_encode($html);
	}

	public function getMessageBody(Request $request, $type)
	{
		switch ($type) {
			case 'pl':
				$this->pl($request);
				break;
			case 'zan':
				$this->zan($request);
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
            'name' => $data->name,
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
