<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Installer;

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
        // Load views.
        $this->loadViewsFrom(dirname(__DIR__).'/../../view', 'pcview');

        $this->publishes([
            dirname(__DIR__).'/../../resource' => $this->app->PublicPath().'/zhiyicx/plus-component-pc',
        ], 'public');

        $this->loadRoutesFrom(
            dirname(__DIR__).'/../../router.php'
        );

        PackageHandler::loadHandleFrom('pc', PcPackageHandler::class);
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
