<?php

namespace QSCMF\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command{
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
    public function handle(FilesSystem $filesystem){
        $this->call('voyager:install');
        $this->call('voyager:admin admin@email.com', ['--create']);
    }
}