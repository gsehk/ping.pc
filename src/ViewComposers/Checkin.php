<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class Checkin
{
    public function compose(View $view)
    {
    	$data = createRequest('GET', '/api/v2/user/checkin');
    	$data['checked_in'] = $data['checked_in'] ? 1 : 0;
        $view->with('data', $data);
    }
}