<?php

namespace App\Console\Commands;

use App\Utilities\Console;
use Illuminate\Console\Command;

class UpdateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:all {company=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to update Akaunting and all modules at once';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0); // unlimited

        $this->info('Starting update...');

        // Update core
        if ($this->runUpdate('core') !== true) {
            $this->error('Not able to update core!');

            return;
        }

        // Update modules
        $modules = module()->all();

        foreach ($modules as $module) {
            $alias = $module->get('alias');

            if ($this->runUpdate($alias) !== true) {
                $this->error('Not able to update ' . $alias . '!');
            }
        }

        $this->info('Update finished.');
    }

    protected function runUpdate($alias)
    {
        $this->info('Updating ' . $alias . '...');

        $company_id = $this->argument('company');

        $command = "update {$alias} {$company_id}";

        if (true !== $result = Console::run($command, true)) {
            $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $alias]);

            $this->error($message);

            return false;
        }

        return true;
    }
}
