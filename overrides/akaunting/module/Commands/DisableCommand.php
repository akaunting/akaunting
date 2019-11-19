<?php

namespace Akaunting\Module\Commands;

use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class DisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {alias} {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alias = Str::kebab($this->argument('alias'));
        $company_id = $this->argument('company_id');

        $model = Module::alias($alias)->companyId($company_id)->first();

        if (!$model) {
            $this->info("Module [{$alias}] not found.");
            return;
        }

        if ($model->enabled == 1) {
            $model->enabled = 0;
            $model->save();

            $module = module($alias);

            // Add history
            $data = [
                'company_id' => $company_id,
                'module_id' => $model->id,
                'category' => $module->get('category', 'payment-method'),
                'version' => $module->get('version'),
                'description' => trans('modules.disabled', ['module' => $module->get('alias')]),
            ];

            ModuleHistory::create($data);

            // Trigger event
            event(new \App\Events\Module\Disabled($alias, $company_id));

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
