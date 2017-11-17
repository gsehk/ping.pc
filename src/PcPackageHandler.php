<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Support\Configuration;
use Zhiyi\Plus\Support\PackageHandler;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class PcPackageHandler extends PackageHandler
{
    /**
     * The config store.
     *
     * @var \Zhiyi\Plus\Support\Configuration
     */
    protected $config;

    /**
     * Create the handler instance.
     *
     * @param \Zhiyi\Plus\Support\Configuration $conft
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }


    public function installHandle($command)
    {
        // publish public assets
        $command->call('vendor:publish', [
            '--provider' => PcServiceProvider::class,
            '--tag' => 'public',
            '--force' => true,
        ]);

        $this->config->set('pc.status', 1);
        $this->config->set('pc.logo', 0);
        $this->config->set('pc.loginbg', 0);
        $this->config->set('pc.site_name', 'ThinkSNS+');
        $this->config->set('pc.site_copyright', 'Powered by ThinkSNS ©2017 ZhishiSoft All Rights Reserved.');
        $this->config->set('pc.site_technical', 'ThinkSNS');
        $this->config->set('pc.weibo.client_id', '');
        $this->config->set('pc.weibo.client_secret', '');
        $this->config->set('pc.wechat.client_id', '');
        $this->config->set('pc.wechat.client_secret', '');
        $this->config->set('pc.qq.client_id', '');
        $this->config->set('pc.qq.client_secret', '');


        // Run the database migrations
        $command->call('migrate');

        if ($command->confirm('Run seeder')) {
            // Run the database seeds.
            $command->call('db:seed', [
                '--class' => \PcDatabaseSeeder::class,
            ]);
        }
    }

    public function removeHandle($command)
    {
        if ($command->confirm('This will delete your datas for pc, continue?')) {

            // delete ads datas
            $space = [
                'pc:news:top',
                'pc:news:right',
                'pc:news:list',
                'pc:feeds:right',
                'pc:feeds:list',
            ];
            $ads = AdvertisingSpace::whereIn('space', $space)->pluck('id');
            AdvertisingSpace::whereIn('space', $space)->delete();
            Advertising::whereIn('space_id', $ads)->delete();

            // close
            $this->config->set('pc.status', 0);

            $command->info('The Pc Component has been removed');
        }
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
