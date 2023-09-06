<?php
declare(strict_types=1);

namespace Plank\Mediable\Commands;

use Illuminate\Console\Command;

/**
 * Synchronize Media Artisan Command.
 */
class SyncMediaCommand extends Command
{
    /**
     * {@inheritdoc}
     * @var string
     */
    protected $signature = 'media:sync {disk : the name of the filesystem disk.}
        {--d|directory= : prune records for files in or below a given directory.}
        {--non-recursive : only prune record for files in the specified directory.}
        {--f|force : re-process existing media.}';

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $description = 'Synchronize media records with the filesystem.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $disk = $this->argument('disk');
        $directory = $this->option('directory') ?: '';
        $non_recursive = (bool)$this->option('non-recursive');
        $force = (bool)$this->option('force');

        $this->call('media:prune', [
            'disk' => $disk,
            '--directory' => $directory,
            '--non-recursive' => $non_recursive,
        ]);

        $this->call('media:import', [
            'disk' => $disk,
            '--directory' => $directory,
            '--non-recursive' => $non_recursive,
            '--force' => $force,
        ]);
    }
}
