<?php

namespace App\Console\Commands;

use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleDisable extends Command
{
    /**
    * The console command name.
    *
    * @var string
    */
    protected $name = 'module:disable {alias} {company_id}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Disable the specified module.';

    /**
    * Execute the console command.
    */
    public function handle()
    {
        $alias = $this->argument('alias');
        $company_id = $this->argument('company_id');

        $model = Module::alias($alias)->companyId($company_id)->get();

        if (!$model) {
            $this->info("Module [{$alias}] not found.");
            return;
        }

        if ($model->enabled == 1) {
            $model->enabled = 0;
            $model->save();

            $module = $this->laravel['modules']->findByAlias($alias);

            // Add history
            $data = [
                'company_id' => $company_id,
                'module_id' => $model->id,
                'category' => $module->get('category'),
                'version' => $module->get('version'),
                'description' => trans('modules.disabled', ['module' => $module->get('name')]),
            ];

            ModuleHistory::create($data);

            $this->info("Module [{$alias}] disabled.");
        } else {
            $this->comment("Module [{$alias}] is already disabled.");
        }
    }

    /**
    * Get the console command arguments.
    *
    * @return array
    */
    protected function getArguments()
    {
        return array(
            array('alias', InputArgument::REQUIRED, 'Module alias.'),
            array('company_id', InputArgument::REQUIRED, 'Company ID.'),
        );
    }
}
