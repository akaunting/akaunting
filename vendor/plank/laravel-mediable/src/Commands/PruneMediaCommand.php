<?php
declare(strict_types=1);

namespace Plank\Mediable\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Plank\Mediable\Media;

/**
 * Prune Media Artisan Command.
 */
class PruneMediaCommand extends Command
{
    /**
     * {@inheritdoc}
     * @var string
     */
    protected $signature = 'media:prune {disk : the name of the filesystem disk.}
        {--d|directory= : prune records for files in or below a given directory.}
        {--non-recursive : only prune record for files in the specified directory.}';

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $description = 'Delete media records that do not correspond to a file on disk';

    /**
     * Filesystem Manager instance.
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * Constructor.
     * @param FilesystemManager $filesystem
     */
    public function __construct(FileSystemManager $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $disk = $this->argument('disk');
        $directory = $this->option('directory') ?: '';
        $recursive = !$this->option('non-recursive');
        $counter = 0;

        $records = $this->makeModel()
            ->newQuery()
            ->inDirectory($disk, $directory, $recursive)
            ->get();

        foreach ($records as $media) {
            if (!$media->fileExists()) {
                $media->delete();
                ++$counter;
                $this->info("Pruned record for file {$media->getDiskPath()}", 'v');
            }
        }

        $this->info("Pruned {$counter} record(s).");
    }

    /**
     * Generate an instance of the `Media` class.
     * @return Media
     */
    private function makeModel(): Media
    {
        $class = config('mediable.model', Media::class);

        return new $class;
    }
}
