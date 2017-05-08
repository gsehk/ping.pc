<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CreditRecord extends Model
{
    protected $table = 'credit_record';


    public function setting()
    {

        return $this->hasOne(CreditSetting::class, 'id', 'cid');
    }
}

