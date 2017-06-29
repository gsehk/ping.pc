<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserVerified extends Model
{
    protected $table = 'user_verified';

    /**
     * 关联认证用户信息
     * 
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 筛选已认证数据
     * 
     * @param  Builder $query [description]
     * @return [type]         [description]
     */
    public function scopeByAudit(Builder $query): Builder
    {
        return $query->where('verified', 1);
    }

}
