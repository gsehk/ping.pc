<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Installer;

use Closure;
use Carbon\Carbon;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\Permission;
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
            Schema::dropIfExists('check_info');
            Schema::dropIfExists('credit_record');
            Schema::dropIfExists('credit_setting');
            Schema::dropIfExists('credit_user');
            Schema::dropIfExists('user_verified');
            Schema::dropIfExists('user_visitor');
            Schema::dropIfExists('denounce');

            $command->info('The Pc Component has been removed');
        }
    }

    public function installHandle($command)
    {

        // 签到表
        if (!Schema::hasTable('check_info')) {
            Schema::create('check_info', function (Blueprint $table) {
                $table->timestamps();
                $table->softDeletes();
            });
            include component_base_path('/databases/table_check_info_column.php');
        }

        // 积分记录
        if (!Schema::hasTable('credit_record')) {
            Schema::create('credit_record', function (Blueprint $table) {
                $table->timestamps();
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_credit_record_column.php');
        }

        // 积分配置
        if (!Schema::hasTable('credit_setting')) {
            Schema::create('credit_setting', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_credit_setting_column.php');
        }

        // 用户积分
        if (!Schema::hasTable('credit_user')) {
            Schema::create('credit_user', function (Blueprint $table) {
                $table->timestamps();
                $table->increments('id')->comment('主键');
            });
            include component_base_path('/databases/table_credit_user_column.php');
        }

        // 用户认证
        if (!Schema::hasTable('user_verified')) {
            Schema::create('user_verified', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_user_verified_column.php');
        }

        // 访客
        if (!Schema::hasTable('user_visitor')) {
            Schema::create('user_visitor', function (Blueprint $table) {
                $table->timestamps();
            });
            include component_base_path('/databases/table_user_visitor_column.php');
        }
            
        // 举报
        if (!Schema::hasTable('denounce')) {
            Schema::create('denounce', function (Blueprint $table) {
                $table->increments('id')->comment('主键');
                $table->timestamps();
            });
            include component_base_path('/databases/table_denounce_column.php');
        }

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
