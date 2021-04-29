<?php

namespace App\Http\ViewComposers;

use App\Traits\Modules;
use Illuminate\View\View;
use Route;

class Suggestions
{
    use Modules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !config('app.installed')) {
            return;
        }

        if ((!$user = user()) || $user->cannot('read-modules-home')) {
            return;
        }

        if (!$path = Route::current()->uri()) {
            return;
        }

        $path = str_replace('{company_id}/', '', $path);

        if (!$suggestions = $this->getSuggestions($path)) {
            return;
        }

        $modules = [];

        foreach ($suggestions->modules as $s_module) {
            if ($this->moduleIsEnabled($s_module->alias)) {
                continue;
            }

            $s_module->action_url = company_id() . '/' . $s_module->action_url;

            $modules[] = $s_module;
        }

        if (empty($modules)) {
            return;
        }

        $view->getFactory()->startPush('header_button_end', view('partials.admin.suggestions', compact('modules')));
    }
}
