<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Traits\Modules as RemoteModules;
use Route;
use App\Models\Module\Module;

class Suggestions
{
    use RemoteModules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !env('APP_INSTALLED')) {
            return;
        }

        $modules = false;

        $path = Route::current()->uri();

        if ($path) {
            $suggestions = $this->getSuggestions($path);

            if ($suggestions) {
                $suggestion_modules = $suggestions->modules;

                foreach ($suggestion_modules as $key => $module) {
                    $installed = Module::where('company_id', '=', session('company_id'))->where('alias', '=', $module->alias)->first();

                    if ($installed) {
                        unset($suggestion_modules[$key]);
                    }
                }

                if ($suggestion_modules) {
                    shuffle($suggestion_modules);

                    $modules[] = $suggestion_modules[0];
                }
            }
        }

        $view->with(['suggestion_modules' => $modules]);
    }
}
