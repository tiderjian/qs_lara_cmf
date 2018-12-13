<?php
namespace QSCMF\Tests\Commands;

use QSCMF\Tests\TestCase;

class UninstallCommandTest extends TestCase{

    public function testUninstallCommand(){
        $this->artisan('migrate');
        $this->artisan("hook:setup");

        $this->artisan('qscmf:uninstallHook')
            ->expectsOutput('clear repositories.hooks setting in the composer.json')
            ->expectsOutput("Deleted [config/hooks.php]")
            ->expectsOutput("Deleted [config/voyager-hooks.php]");
    }
}