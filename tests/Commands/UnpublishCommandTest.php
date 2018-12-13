<?php
namespace QSCMF\Tests\Commands;

use Illuminate\Foundation\Testing\PendingCommand;
use League\Flysystem\Util;
use QSCMF\Tests\TestCase;

class UnpublishCommandTest extends TestCase
{

    public function testUnpublishCommand(){
        $this->publish('TCG\Voyager\VoyagerServiceProvider', ['seeds']);
        $this->checkSeeds('publish');

        $this->unpublish('TCG\Voyager\VoyagerServiceProvider', ['seeds']);
        $this->checkSeeds('unpublish');


        $this->publish('TCG\Voyager\VoyagerServiceProvider', ['seeds', 'config']);
        $this->checkSeeds('publish');
        $this->checkConfig('publish');

        $this->unpublish('TCG\Voyager\VoyagerServiceProvider', ['seeds', 'config']);
        $this->checkSeeds('unpublish');
        $this->checkConfig('unpublish');

        $this->publish('TCG\Voyager\VoyagerServiceProvider');
        $this->checkSeeds('publish');
        $this->checkConfig('publish');
        $this->checkAssets('publish');

        $this->unpublish('TCG\Voyager\VoyagerServiceProvider');
        $this->checkSeeds('unpublish');
        $this->checkConfig('unpublish');
        $this->checkAssets('unpublish');

        $this->unpublish('')->expectsOutput('Please support the provider.');

    }

    protected function publish(string $provider, Array $tags = []) : PendingCommand{
        if(!$tags)
            return $this->artisan('vendor:publish', [
                '--provider' => $provider,
                '--tag' =>$tags
            ]);
        else
            return $this->artisan('vendor:publish', [
                '--provider' => $provider
            ]);
    }

    protected function unpublish(string $provider, Array $tags = []) : PendingCommand{
        if(!$tags)
            return $this->artisan('qscmf:unpublish', [
                '--provider' => $provider,
                '--tag' => $tags
            ]);
        else
            return $this->artisan('qscmf:unpublish', [
                '--provider' => $provider
            ]);
    }

    protected function checkSeeds(string $type) : void{
        $test_file = Util::normalizePath(database_path('seeds/DataRowsTableSeeder.php'));
        $test_dir = Util::normalizePath(database_path('seeds'));

        if($type == 'publish'){
            $this->assertFileExists($test_file);
            $this->assertDirectoryExists($test_dir);
        }
        else{
            $this->assertFileNotExists($test_file);
            $this->assertDirectoryNotExists($test_dir);
        }
    }

    protected function checkConfig(string $type) : void{
        $test_file = Util::normalizePath(config_path('voyager.php'));

        if($type == 'publish'){
            $this->assertFileExists($test_file);
        }
        else{
            $this->assertFileNotExists($test_file);
        }
    }

    protected function checkAssets(string $type) :void{
        $test_file = Util::normalizePath(public_path(config('voyager.assets_path')) . '/css/app.css');
        $test_dir = Util::normalizePath(public_path(config('voyager.assets_path')) . '/css');

        if($type == 'publish'){
            $this->assertFileExists($test_file);
            $this->assertDirectoryExists($test_dir);
        }
        else{
            $this->assertFileNotExists($test_file);
            $this->assertDirectoryNotExists($test_dir);
        }
    }
}
