<?php 

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
* 用户签到模型
*/
class CheckInfo extends Model
{
    protected $table = 'check_info';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
