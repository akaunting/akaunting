<?php

namespace App\Http\ViewComposers;

use App\Models\Addon\Addon;
use App\Traits\Modules as RemoteModules;
use Cache;
use Date;
use Illuminate\View\View;

class Modules
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
        if (setting('general.api_token')) {
            $categories = Cache::remember('modules.categories', Date::now()->addHour(6), function () {
                return collect($this->getCategories())->pluck('name', 'slug')
                    ->prepend(trans('categories.all'), '');
            });

            $view->with(['categories' => $categories]);
        }
    }
}
