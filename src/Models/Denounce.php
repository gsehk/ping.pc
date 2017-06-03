<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Denounce extends Model
{
    protected $table = 'denounce';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeByAudit(Builder $query): Builder
    {
        return $query->where('state', 1);
    }
}
