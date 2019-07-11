<?php

namespace App\Console\Commands;

use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleEnable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable {alias} {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alias = $this->argument('alias');
        $company_id = $this->argument('company_id');

        $model = Module::alias($alias)->companyId($company_id)->first();

        if (!$model) {
            $this->info("Module [{$alias}] not found.");
            return;
        }

        if ($model->status == 0) {
            $model->status = 1;
            $model->save();

            $module = $this->laravel['modules']->findByAlias($alias);

            // Add history
            $data = [
                'company_id' => $company_id,
                'module_id' => $model->id,
                'category' => $module->get('category'),
                'version' => $module->get('version'),
                'description' => trans('modules.enabled', ['module' => $module->get('name')]),
            ];

            ModuleHistory::create($data);

            $this->info("Module [{$alias}] enabled.");
        } else {
            $this->comment("Module [{$alias}] is already enabled.");
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
