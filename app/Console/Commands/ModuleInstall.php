<?php

namespace App\Console\Commands;

use App\Events\ModuleInstalled;
use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {alias} {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = [
            'company_id' => $this->argument('company_id'),
            'alias' => strtolower($this->argument('alias')),
            'status' => '1',
        ];

        $model = Module::create($request);

        $module = $this->laravel['modules']->findByAlias($model->alias);

        $company_id = $this->argument('company_id');

        // Add history
        $data = [
            'company_id' => $company_id,
            'module_id' => $model->id,
            'category' => $module->get('category'),
            'version' => $module->get('version'),
            'description' => trans('modules.installed', ['module' => $module->get('name')]),
        ];

        ModuleHistory::create($data);

        // Clear cache
        $this->call('cache:clear');

        // Update database
        $this->call('migrate', ['--force' => true]);

        // Trigger event
        event(new ModuleInstalled($model->alias, $company_id));

        $this->info('Module installed!');
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
