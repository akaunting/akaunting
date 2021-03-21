<?php

namespace Akaunting\Module\Commands;

use Illuminate\Console\Command;
use Akaunting\Module\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified module or for all modules.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alias = $this->argument('alias');

        if ($alias) {
            $this->updateModule($alias);

            return;
        }

        /** @var \Akaunting\Module\Module $module */
        foreach ($this->laravel['module']->getOrdered() as $module) {
            $this->updateModule($module->getAlias());
        }
    }

    protected function updateModule($alias)
    {
        $this->line('Running for module: <info>' . $alias . '</info>');

        $this->laravel['module']->update($alias);

        $this->info("Module [{$alias}] updated successfully.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['alias', InputArgument::OPTIONAL, 'The alias of module will be updated.'],
        ];
    }
}
