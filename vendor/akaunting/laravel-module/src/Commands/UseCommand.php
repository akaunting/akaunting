<?php

namespace Akaunting\Module\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alias = Str::kebab($this->argument('alias'));

        if (!$this->laravel['module']->has($alias)) {
            $this->error("Module [{$alias}] does not exists.");

            return;
        }

        $this->laravel['module']->setUsed($alias);

        $this->info("Module [{$alias}] used successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['alias', InputArgument::REQUIRED, 'The alias of module will be used.'],
        ];
    }
}
