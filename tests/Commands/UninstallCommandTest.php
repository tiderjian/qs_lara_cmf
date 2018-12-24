<?php
namespace QSCMF\Tests\Commands;

use QSCMF\Tests\TestCase;

class UninstallCommandTest extends TestCase{

    public function testUninstallCommand(){
        $this->artisan("qscmf:install")
            ->expectsQuestion("'Enter the admin name", "admin")
            ->expectsQuestion("Enter admin password", "123456")
            ->expectsQuestion("Confirm Password", "123456");

        $this->artisan("qscmf:uninstall")
            ->expectsOutput("Deleted [" . storage_path('app/public/users/default.png') . "]")
            ->expectsOutput("Deleted directory [" . public_path(config('voyager.assets_path')) . "]")
            ->expectsOutput("Deleted directory [" . database_path('seeds') . "]");

//        $this->artisan('migrate');
//        $this->artisan("hook:setup");
//
//        $this->artisan('qscmf:uninstallHook')
//            ->expectsOutput('clear repositories.hooks setting in the composer.json')
//            ->expectsOutput("Deleted [config/hooks.php]")
//            ->expectsOutput("Deleted [config/voyager-hooks.php]");
    }

}