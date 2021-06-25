<?php

namespace App\Abstracts\Commands;

use App\Models\Module\Module as Model;
use App\Models\Module\ModuleHistory as ModelHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

abstract class Module extends Command
{
    protected function prepare()
    {
        $this->alias = Str::kebab($this->argument('alias'));
        $this->company_id = $this->argument('company');
        $this->locale = $this->argument('locale');

        $this->module = module($this->alias);
    }

    protected function changeRuntime()
    {
        $this->old_company_id = company_id();

        company($this->company_id)->makeCurrent();

        app()->setLocale($this->locale);

        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);
    }

    protected function revertRuntime()
    {
        session()->forget('company_id');

        if (!empty($this->old_company_id)) {
            company($this->old_company_id)->makeCurrent();
        }
    }

    protected function getModel()
    {
        $this->model = Model::companyId($this->company_id)->alias($this->alias)->first();

        return $this->model;
    }

    protected function createHistory($action)
    {
        if (empty($this->model)) {
            return;
        }

        ModelHistory::create([
            'company_id' => $this->company_id,
            'module_id' => $this->model->id,
            'version' => $this->module->get('version'),
            'description' => trans('modules.' . $action, ['module' => $this->alias]),
        ]);
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
            ['company', InputArgument::REQUIRED, 'Company ID.'],
        ];
    }
}
