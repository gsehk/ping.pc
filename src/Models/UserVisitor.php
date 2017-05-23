<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserVisitor extends Model
{
    protected $table = 'user_visitor';

    /**
     * 访问者信息
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 被访问者信息
     * @return [type] [description]
     */
    public function toUser()
    {
    	return $this->hasOne(User::class, 'id', 'to_uid');
    }
}
