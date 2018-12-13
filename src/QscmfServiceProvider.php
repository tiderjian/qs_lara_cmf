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
