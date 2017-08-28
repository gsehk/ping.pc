<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Blade;
use Illuminate\Support\Facades\View;
use Zhiyi\Plus\Support\PackageHandler;
use Illuminate\Support\ServiceProvider;
use Zhiyi\Plus\Support\ManageRepository;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\{
    asset
};

class PcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // load views.
        $this->loadViewsFrom(dirname(__DIR__).'/view', 'pcview');

        // publish resource
        $this->publishes([
            dirname(__DIR__).'/resource' => $this->app->PublicPath().'/zhiyicx/plus-component-pc',
        ], 'public');

        // load routes
        $this->loadRoutesFrom(
            dirname(__DIR__).'/router.php'
        );

        // load handle
        PackageHandler::loadHandleFrom('pc', PcPackageHandler::class);

        // load view composers
        View::composer('pcview::widgets.hotusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotUsers');
        View::composer('pcview::widgets.recusers', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\RecommendUsers');
        View::composer('pcview::widgets.checkin', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\CheckIn');
        View::composer('pcview::widgets.hotnews', 'Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers\HotNews');
    }

    /**
     * register provided to provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        $this->app->make(ManageRepository::class)->loadManageFrom('PC端', 'pc:admin', [
            'route' => true,
            'icon' => asset('pc-icon.png'),
        ]);
    }

    /**
     * Register route.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function routeMap()
    {
        $this->app->make(RouteRegistrar::class)->all();
    }
}
