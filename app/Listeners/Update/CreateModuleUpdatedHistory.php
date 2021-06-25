<?php

namespace App\Listeners\Update;

use App\Events\Install\UpdateFinished as Event;
use App\Models\Module\Module;
use App\Models\Module\ModuleHistory;

class CreateModuleUpdatedHistory
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $model = Module::where('alias', $event->alias)->first();

        if (empty($model)) {
            return;
        }

        // Get module instance
        $module = module($event->alias);

        if (empty($module)) {
            return;
        }

        // Add history
        ModuleHistory::create([
            'company_id' => $model->company_id,
            'module_id' => $model->id,
            'version' => $event->new,
            'description' => trans('modules.updated_2', ['module' => $module->getAlias()]),
        ]);
    }
}
