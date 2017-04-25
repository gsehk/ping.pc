<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Installer;

use Closure;
use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\{
    route_path,
    resource_path,
    base_path as component_base_path
};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Installer extends AbstractInstaller
{
    /**
     * Get the component info onject.
     *
     * @return Zhiyi\Component\ZiyiPlus\PlusComponentPc\Installer\Info
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function getComponentInfo()
    {
        return new Info();
    }

    /**
     * Get the component route file.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function router()
    {
        return route_path();
    }

    /**
     * Get the component resource dir.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function resource()
    {
        return resource_path();
    }

    /**
     * Do run the cpmponent install.
     *
     * @param Closure $next
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function install(Closure $next)
    {
        $next();
    }

    /**
     * Do run update the compoent.
     *
     * @param Closure $next
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function update(Closure $next)
    {
        $next();
    }

    public function uninstall(Closure $next)
    {
        $next();
    }
}
