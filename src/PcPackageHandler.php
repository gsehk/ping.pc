<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Closure;
use Carbon\Carbon;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Permission;
use Zhiyi\Plus\Models\AdvertisingSpace;
use Zhiyi\Component\Installer\PlusInstallPlugin\AbstractInstaller;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\{
    route_path,
    resource_path,
    base_path as component_base_path
};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Support\PackageHandler;

class PcPackageHandler extends PackageHandler
{
    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for pc, continue?')) {
            Comment::where('component', 'pc')->delete();
            $command->info('The Pc Component has been removed');
        }
    }

    public function installHandle($command)
    {
        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:top',
            'alias' => '资讯首页banner',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);


        AdvertisingSpace::create([
            'channel' => 'pc',
            'space' => 'pc:news:right',
            'alias' => '资讯右侧广告',
            'allow_type' => 'image',
            'format' => [
                'image' => [
                    'image' => '图片|string',
                    'link' => '链接|string',
                ],
            ],
        ]);

        $command->info('Install Successfully');
    }

    /**
     * Create a soft link to public.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function linkHandle($command)
    {
        if (! $command->confirm('Use a soft link to publish assets')) {
            return;
        }
        $this->unlink();
        $files = app('files');
        
        foreach ($this->getPaths() as $target => $link) {
            $parentDir = dirname($link);
            if (! $files->isDirectory($parentDir)) {
                $files->makeDirectory($parentDir);
            }
            $files->link($target, $link);
            $command->line(sprintf('<info>Created Link</info> <comment>[%s]</comment> <info>To</info> <comment>[%s]</comment>', $target, $link));
        }
        $command->info('Linking complete.');
    }

    /**
     * Delete links.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function unlink()
    {
        $files = app('files');
        foreach ($this->getPaths() as $path) {
            if (! $files->delete($path)) {
                $files->deleteDirectory($path, false);
            }
        }
    }
    
    /**
     * Get the Publish path,
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getPaths(): array
    {
        return PcServiceProvider::pathsToPublish(PcServiceProvider::class, 'public');
    }

}
