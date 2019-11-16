<?php

namespace App\Http\ViewComposers;

use Date;
use Illuminate\Support\Str;
use Illuminate\View\View;

class Index
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $limits = ['10' => '10', '25' => '25', '50' => '50', '100' => '100'];

        $now = Date::now();

        $this_year = $now->year;

        $years = [];
        $y = $now->addYears(2);
        for ($i = 0; $i < 10; $i++) {
            $years[$y->year] = $y->year;
            $y->subYear();
        }

        $view->with(['limits' => $limits, 'this_year' => $this_year, 'years' => $years]);

        // Add Bulk Action
        $module = false;
        $view_name = $view->getName();

        if (Str::contains($view_name, '::')) {
            $names = explode('::', $view_name);

            $params = explode('.', $view_name);

            $type = $params[0];

            // Check is module
            $module = module($names[0]);
        } else {
            $params = explode('.', $view_name);

            $group = $params[0];
            $type = $params[1];
        }

        if ($module instanceof \Akaunting\Module\Module) {
            $class = 'Modules\\' . $module->getStudlyName() . '\BulkActions\\' . ucfirst($type);
        } else {
            $class = 'App\BulkActions\\' .  ucfirst($group) . '\\' . ucfirst($type);
        }

        if (class_exists($class)) {
            $bulk_actions = app($class);

            $view->with(['bulk_actions' => $bulk_actions->actions]);
        }
    }
}
