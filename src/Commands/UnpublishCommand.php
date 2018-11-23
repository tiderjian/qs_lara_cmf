<?php
namespace QSCMF\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class UnpublishCommand extends Command {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'qscmf:unpublish
                    {--provider= : The service provider that has assets you want to publish}
                    {--tag=* : One or many tags that have assets you want to publish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish assets which had published from vendor packages';

    /**
     * The provider to publish.
     *
     * @var string
     */
    protected $provider = null;

    /**
     * The tags to publish.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void{
        [$this->provider, $this->tags] = [
            $this->option('provider'), $this->option('tag'),
        ];

        if(!$this->provider){
            $this->error("Please support the provider.");
        }

        foreach ($this->tags ?: [null] as $tag) {
            $this->unpublishTag($tag);
        }


    }

    /**
     * Unpublishes the assets for a tag.
     *
     * @param  string  $tag
     * @return void
     */
    protected function unpublishTag(string $tag) : void
    {
        foreach ($this->pathsToPublish($tag) as $from => $to) {
            $this->unpublishItem($from, $to);
        }
    }


    /**
     * Get all of the paths to publish.
     *
     * @param  string  $tag
     * @return array
     */
    protected function pathsToPublish(string $tag) : array
    {
        return ServiceProvider::pathsToPublish(
            $this->provider, $tag
        );
    }

    /**
     * Unpublish the given item which had published.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    protected function unpublishItem(string $from, string $to) : void
    {
        if ($this->files->isFile($from)) {
            return $this->deleteFile($from, $to);
        } elseif ($this->files->isDirectory($from)) {
            return $this->publishDirectory($from, $to);
        }

        $this->error("Can't locate path: <{$from}>");
    }

    /**
     * delete the file in the given path.
     *
     * @param  string  $to
     * @return void
     */
    protected function deleteFile(string $to) : void
    {
        if ($this->files->exists($to) ) {
            $this->files->delete($to) ? $this->status($to, true) : $this->status($to, false);
        }
    }

    /**
     * @param string $to
     * @param bool $status
     */
    protected function status(string $to, bool $status) : void
    {
        $to = str_replace(base_path(), '', realpath($to));

        if($status){
            $this->line('<info>Deleted</info> <comment>['.$to.']</comment> ');
        }
        else{
            $this->error("Failed to delete {$to}");
        }
    }
}