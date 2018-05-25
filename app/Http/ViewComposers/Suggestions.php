<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Traits\Modules;
use Route;
use App\Models\Module\Module;

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
        $suggestion_module = false;

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

                    $suggestion_module[] = $suggestion_modules[0];
                }
            }
        }

        $view->with(['suggestion_modules' => $suggestion_module]);
    }
}
