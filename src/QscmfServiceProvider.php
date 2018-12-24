<?php
namespace QSCMF;

use Illuminate\Support\ServiceProvider;
use QSCMF\Commands\InstallCommand;
use QSCMF\Commands\UninstallCommand;
use QSCMF\Commands\UnpublishCommand;

class QscmfServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(InstallCommand::class);
            $this->commands(UninstallCommand::class);
            $this->commands(UnpublishCommand::class);
        }

        $this->loadHelpers();
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $defaultAvatarPath = $this->app['path.base'] . '/vendor/tcg/voyager/publishable/dummy_content/users/default.png';

            $this->app['files']->exists($defaultAvatarPath)
            && $this->publishes([$defaultAvatarPath => storage_path('app/public/users/default.png')], 'default_avatar');
        }
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }
}
