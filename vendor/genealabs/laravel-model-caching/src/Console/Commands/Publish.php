<?php namespace GeneaLabs\LaravelModelCaching\Console\Commands;

use GeneaLabs\LaravelModelCaching\Providers\Service;
use Illuminate\Console\Command;

class Publish extends Command
{
    protected $signature = 'modelCache:publish {--assets} {--config} {--views} {--migrations}';
    protected $description = "Publish various assets of the 'Model Caching for Laravel' package.";

    public function handle()
    {
        if ($this->option('assets')) {
            $this->call('casts:publish', ['--assets' => true]);
        }

        if ($this->option('config')) {
            $this->call('vendor:publish', [
                '--provider' => Service::class,
                '--tag' => ['config'],
                '--force' => true,
            ]);
        }

        if ($this->option('views')) {
            $this->call('vendor:publish', [
                '--provider' => Service::class,
                '--tag' => ['views'],
                '--force' => true,
            ]);
        }

        if ($this->option('migrations')) {
            $this->call('vendor:publish', [
                '--provider' => Service::class,
                '--tag' => ['migrations'],
                '--force' => true,
            ]);
        }
    }
}
