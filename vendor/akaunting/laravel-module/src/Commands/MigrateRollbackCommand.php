<?php

namespace Akaunting\Module\Commands;

use Illuminate\Console\Command;
use Akaunting\Module\Migrations\Migrator;
use Akaunting\Module\Traits\MigrationLoaderTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateRollbackCommand extends Command
{
    use MigrationLoaderTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the modules migrations.';

    /**
     * @var \Akaunting\Module\Contracts\RepositoryInterface
     */
    protected $module;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->module = $this->laravel['module'];

        $alias = $this->argument('alias');

        if (!empty($alias)) {
            $this->rollback($alias);

            return;
        }

        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->line('Running for module: <info>' . $module->getAlias() . '</info>');

            $this->rollback($module);
        }
    }

    /**
     * Rollback migration from the specified module.
     *
     * @param $module
     */
    public function rollback($module)
    {
        if (is_string($module)) {
            $module = $this->module->findOrFail($module);
        }

        $migrator = new Migrator($module, $this->getLaravel());

        $database = $this->option('database');

        if (!empty($database)) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->line("Rollback: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['alias', InputArgument::OPTIONAL, 'The alias of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'desc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
        ];
    }
}
