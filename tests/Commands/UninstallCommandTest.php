<?php
namespace QSCMF\Tests\Commands;

use QSCMF\Tests\TestCase;

class UninstallCommandTest extends TestCase{

    public function testUninstallCommand(){
        $this->artisan("qscmf:install")
            ->expectsQuestion("Enter the admin name", "admin")
            ->expectsQuestion("Enter admin password", "123456")
            ->expectsQuestion("Confirm Password", "123456");

        $this->artisan("qscmf:uninstall")
            ->expectsOutput($this->deleteFileOutput(storage_path('app/public/users/default.png')))
            ->expectsOutput($this->deleteDirectoryOutput(public_path(config('voyager.assets_path'))))
            ->expectsOutput($this->deleteDirectoryOutput(database_path('seeds')))
            ->expectsOutput($this->deleteFileOutput(config_path('imagecache.php')))
            ->expectsOutput("Rolled back:  2017_11_26_015000_create_user_roles_table")
            ->expectsOutput("Revert " . app_path('User.php'))
            ->expectsOutput("delete Voyager routes from routes/web.php")
            ->expectsOutput($this->deleteFileOutput(config_path('voyager.php')))
            ->expectsOutput("clear repositories.hooks setting in the composer.json")
            ->expectsOutput($this->deleteFileOutput(config_path('hooks.php')))
            ->expectsOutput($this->deleteFileOutput(config_path('voyager-hooks.php')))
            ->expectsOutput("delete storage link.");

//        $this->artisan('migrate');
//        $this->artisan("hook:setup");
//
//        $this->artisan('qscmf:uninstallHook')
//            ->expectsOutput('clear repositories.hooks setting in the composer.json')
//            ->expectsOutput("Deleted [config/hooks.php]")
//            ->expectsOutput("Deleted [config/voyager-hooks.php]");
    }

    protected function deleteFileOutput($path){
        return "Deleted [" . normalize_path($path) . "]";
    }

    protected  function deleteDirectoryOutput($path){
        return "Deleted directory [" . normalize_path($path) . "]";
    }

}