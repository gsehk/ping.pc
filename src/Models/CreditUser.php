<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreditUser extends Model
{
    protected $table = 'credit_user';

    public function record()
    {

        return $this->hasMany(CreditRecord::class, 'user_id', 'user_id');
    }
}
