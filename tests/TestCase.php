<?php
namespace QSCMF\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use QSCMF\QscmfServiceProvider;
use TCG\Voyager\Models\User;
use TCG\Voyager\VoyagerServiceProvider;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        if (!is_dir(base_path('routes'))) {
            mkdir(base_path('routes'));
        }

        if (!file_exists(base_path('routes/web.php'))) {
            file_put_contents(
                base_path('routes/web.php'),
                "<?php Route::get('/', function () {return view('welcome');});"
            );
        }
    }

    protected function getPackageProviders($app)
    {
        return [QscmfServiceProvider::class, VoyagerServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup Voyager configuration
        $app['config']->set('voyager.user.namespace', User::class);

        // Setup Authentication configuration
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
