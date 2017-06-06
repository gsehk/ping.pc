<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserVerified extends Model
{
    protected $table = 'user_verified';

    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

}
