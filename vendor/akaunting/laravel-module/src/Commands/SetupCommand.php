<?php

namespace Akaunting\Module\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up modules folders for first use.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->generateModulesFolder();

        $this->generateAssetsFolder();
    }

    /**
     * Generate the modules folder.
     */
    public function generateModulesFolder()
    {
        $this->generateDirectory(
            $this->laravel['module']->config('paths.modules'),
            'Modules directory created successfully',
            'Modules directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    public function generateAssetsFolder()
    {
        $this->generateDirectory(
            $this->laravel['module']->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param $dir
     * @param $success
     * @param $error
     */
    protected function generateDirectory($dir, $success, $error)
    {
        if (!$this->laravel['files']->isDirectory($dir)) {
            $this->laravel['files']->makeDirectory($dir, 0755, true, true);

            $this->info($success);

            return;
        }

        $this->error($error);
    }
}
