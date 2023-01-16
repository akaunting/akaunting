<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StorageTempClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage-temp:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all storage/app/temp files';

    /**
     * Execute the console command.
     *
     * @return void
     *
     */
    public function handle()
    {
        $filesystem = app(Filesystem::class);

        $path = storage_path('app/temp');

        foreach ($filesystem->glob("{$path}/*") as $file) {
            $filesystem->delete($file);
        }

        $this->info('Temporary files cleared!');
    }
}
