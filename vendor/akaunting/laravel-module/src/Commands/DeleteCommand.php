<?php

namespace Akaunting\Module\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DeleteCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the specified module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->laravel['module']->findOrFail($this->argument('alias'));

        $module->delete();

        $this->info("Module [{$module}] deleted successful.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['alias', InputArgument::REQUIRED, 'Module alias.'],
        ];
    }
}
