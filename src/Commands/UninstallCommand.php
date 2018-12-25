<?php
namespace QSCMF\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Larapack\Hooks\Composer;
use Larapack\Hooks\HooksServiceProvider;
use Larapack\VoyagerHooks\VoyagerHooksServiceProvider;
use QSCMF\QscmfServiceProvider;
use TCG\Voyager\VoyagerServiceProvider;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'qscmf:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the QSCMF Admin package';

    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->filesystem = $files;
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle()
    {
        $this->call("qscmf:unpublish", ['--provider' => QscmfServiceProvider::class]);
        $this->uninstallVoyager();
        $this->uninstallHook();
        $this->deleteStorageLink();
    }

    protected function uninstallVoyager()
    {
        $this->info('Unpublishing the Voyager assets, database, and config files');

        $tags = ['voyager_assets', 'seeds'];

        $this->call('qscmf:unpublish', ['--provider' => VoyagerServiceProvider::class, '--tag' => $tags]);
        $this->call('qscmf:unpublish', ['--provider' => ImageServiceProviderLaravel5::class]);

        $this->info('database reset');
        $this->call('migrate:reset');

        $this->info('Attempting to return laravel User model');
        if (file_exists(app_path('User.php'))) {
            $str = file_get_contents(app_path('User.php'));

            if ($str !== false) {
                $str = str_replace("extends \TCG\Voyager\Models\User", 'extends Authenticatable', $str);

                file_put_contents(app_path('User.php'), $str);

                $this->info("Revert " . app_path('User.php'));
            }
        }

        $routes_contents = $this->filesystem->get(base_path('routes/web.php'));
        if (false !== strpos($routes_contents, 'Voyager::routes()')) {
            $routes_contents = str_replace("\n\nRoute::group(['prefix' => 'admin'], function () {\n    Voyager::routes();\n});\n", "", $routes_contents);
            $this->filesystem->put(base_path('routes/web.php'), $routes_contents);
            $this->info("delete Voyager routes from routes/web.php");
        }

        $this->call('qscmf:unpublish', ['--provider' => VoyagerServiceProvider::class, '--tag' => 'config']);
    }

    protected function uninstallHook()
    {
        $composer = new Composer(base_path('composer.json'));

        $hooks = $composer->get("repositories.hooks", null);

        if ($hooks) {
            $composer->set("repositories.hooks", null)->save();
            $this->info("clear repositories.hooks setting in the composer.json");
        }

        $this->call('qscmf:unpublish', ['--provider' => HooksServiceProvider::class]);

        $this->call("qscmf:unpublish", ['--provider' => VoyagerHooksServiceProvider::class]);
    }


    protected function deleteStorageLink()
    {
        if ($this->filesystem->exists(public_path('storage'))) {
            $this->filesystem->delete(public_path('storage'));
            $this->info("delete storage link.");
        }
    }
}
