<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;

class RecommendUser
{
    public function compose(View $view)
    {
        $users = [1,2,3];
        $view->with('users', $users);
    }
}