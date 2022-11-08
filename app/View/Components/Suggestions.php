<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Traits\Modules;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Suggestions extends Component
{
    use Modules;

    /** @var objcet */
    public $suggestions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($suggestions = [])
    {
        $this->suggestions = $this->setSuggestions($suggestions);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.suggestions');
    }

    protected function setSuggestions()
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !config('app.installed')) {
            return [];
        }

        if ((! $user = user()) || $user->cannot('read-modules-home')) {
            return [];
        }

        if (! $path = Route::current()->uri()) {
            return [];
        }

        $path = str_replace('{company_id}/', '', $path);

        if (! $suggestions = $this->getSuggestions($path)) {
            return [];
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
            return [];
        }

        if (count($modules) < 3) {
            return $modules;
        }

        return Arr::random($modules, 2);
    }
}
