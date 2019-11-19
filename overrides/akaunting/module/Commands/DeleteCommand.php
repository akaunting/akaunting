<?php

namespace Akaunting\Module\Commands;

use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class DeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:delete {alias} {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the specified module.';

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

        $module = module($alias);
        $module->delete();

        $model->enabled = 0;
        $model->save();

        // Add history
        $data = [
            'company_id' => $company_id,
            'module_id' => $model->id,
            'category' => $module->get('category', 'payment-method'),
            'version' => $module->get('version'),
            'description' => trans('modules.deleted', ['module' => $module->get('alias')]),
        ];

        ModuleHistory::create($data);

        // Trigger event
        event(new \App\Events\Module\Deleted($alias, $company_id));

        Artisan::call('cache:clear');

        $this->info("Module [{$alias}] deleted.");
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
