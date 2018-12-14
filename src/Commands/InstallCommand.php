<?php

namespace QSCMF\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use QSCMF\QscmfServiceProvider;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'qscmf:install';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Install the QSCMF Admin package';

    /**
    * Execute the console command.
    *
    * @param \Illuminate\Filesystem\Filesystem $filesystem
    *
    * @return void
    */
    public function handle(Filesystem $filesystem)
    {
        $this->info("Attempting to set locale to zh_CN");
        if ($filesystem->exists(config_path('app.php'))) {
            $str = $filesystem->get(config_path('app.php'));

            if ($str !== false) {
                $str = str_replace("'locale' => 'en'", "'locale' => 'zh_CN'", $str);

                $filesystem->put(config_path('app.php'), $str);
            }
        } else {
            $this->warn('Unable to locate "config/app.php".  Did you move this file?');
            $this->warn('You will need to update this manually.  Change "locale" to "zh_CN" in your app.php');
        }
        $this->getLaravel()['config']->set('app.locale', 'zh_CN');

        $this->call("vendor:publish", ['--provider' => QscmfServiceProvider::class]);

        $this->call('voyager:install');

        $this->call('voyager:admin', ['email' => 'admin@email.com', '--create' => true]);
    }
}
