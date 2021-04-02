<?php

namespace App\Http\ViewComposers;

use Akaunting\Module\Module;
use App\Events\Common\BulkActionsAdding;
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
        $this->addLimits($view);

        $this->addBulkActions($view);
    }

    protected function addLimits(&$view)
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
    }

    protected function addBulkActions(&$view)
    {
        $class_name = '';
        $view_name = str_replace('.index', '', $view->getName());

        if (Str::contains($view_name, '::')) {
            // my-blog::posts
            $names = explode('::', $view_name);

            $module = module($names[0]);

            if (!$module instanceof Module) {
                return;
            }

            $tmp = explode('.', $names[1]);
            $file_name = !empty($tmp[1]) ? Str::studly($tmp[0]) . '\\' . Str::studly($tmp[1]) : Str::studly($tmp[0]);

            $class_name = 'Modules\\' . $module->getStudlyName() . '\BulkActions\\' . $file_name;
        } else {
            // common.items
            $tmp = explode('.', $view_name);
            $file_name = !empty($tmp[1]) ? Str::studly($tmp[0]) . '\\' . Str::studly($tmp[1]) : Str::studly($tmp[0]);

            $class_name = 'App\BulkActions\\' .  $file_name;
        }

        if (class_exists($class_name)) {
            event(new BulkActionsAdding(app($class_name)));

            $bulk_actions = app($class_name)->actions;
        } else {
            $b = new \stdClass();
            $b->actions = [];

            event(new BulkActionsAdding($b));

            $bulk_actions = $b->actions;
        }

        $view->with(['bulk_actions' => $bulk_actions]);
    }
}
