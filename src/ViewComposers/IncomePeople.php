<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Illuminate\View\View;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\createRequest;

class IncomePeople
{
    public function compose(View $view)
    {
        $params = [
            'limit' => 5
        ];
        $api = '/api/v2/ranks/income';

        $incomes = createRequest('GET', $api, $params);
//        dd($incomes->toArray());

        $view->with('incomes', $incomes);
    }
}