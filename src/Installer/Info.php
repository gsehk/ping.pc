<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Installer;

use Zhiyi\Component\Installer\PlusInstallPlugin\ComponentInfoInterface;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\{
    asset
};

class Info implements ComponentInfoInterface
{
    /**
     * Get the component name.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function getName(): string
    {
        return 'Pc';
    }

    /**
     * Get the component logo.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function getLogo(): string
    {
        return asset('logo.png');
    }

    /**
     * Get the component Icon.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function getIcon(): string
    {
        return asset('icon.png');
    }

    /**
     * Get the component admin entry.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://Zhiyi.cn
     */
    public function getAdminEntry()
    {
        return 'www.baidu.com';
    }
}
