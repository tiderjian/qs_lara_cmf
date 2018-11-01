<?php
namespace QSCMF;

use Illuminate\Support\ServiceProvider;
use QSCMF\Commands\InstallCommand;

class QscmfServiceProvider extends ServiceProvider{

    public function register(){
        if($this->app->runningInConsole()){
            $this->commands(InstallCommand::class);
        }
    }

    public function boot(){
        
    }

    
}