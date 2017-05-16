<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Providers;

use Illuminate\Support\ServiceProvider;

class PcProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__ . '/../../view'), 'pcview');
    }
}
