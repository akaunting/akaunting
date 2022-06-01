<?php

namespace App\View\Components\Layouts\Modules;

use App\Abstracts\View\Component;
use App\Traits\Modules;
use App\Utilities\Date;
use Illuminate\Support\Facades\Cache;

class Bar extends Component
{
    use Modules;

    public $keyword;

    /** array */
    public $categories = [];

    /** array */
    public $popular = [];

    public function categoryUrl($slug)
    {
        if ($slug == '*') {
            return route('apps.home.index');
        }

        return route('apps.categories.show', $slug);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->keyword = request()->get('keyword');
        $this->categories = $this->getCategories();
        $this->popular = $this->getPopularModules();

        return view('components.layouts.modules.bar');
    }

    protected function getCategories()
    {
        $categories = collect([
            '*' => trans('general.all_type', ['type' => trans_choice('general.categories', 2)]),
        ]);

        if (setting('apps.api_key')) {
            $categories = Cache::remember('modules.categories.' . language()->getShortCode(), Date::now()->addHour(6), function () {
                return collect($this->getCategoriesOfModules())->pluck('name', 'slug')
                    ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '*');
            });
        }

        return $categories;
    }
}
