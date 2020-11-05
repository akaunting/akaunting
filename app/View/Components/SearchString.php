<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class SearchString extends Component
{
    public $filters;

    public $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $searc_string = config('search-string');

        $this->filters = false;

        if (!empty($searc_string[$this->model])) {
            $columns = $searc_string[$this->model]['columns'];

            foreach ($columns as $column => $options) {
                if (!empty($options['searchable'])) {
                    continue;
                }
    
                if (!is_array($options)) {
                    $column = $options;
                }

                $name = $this->getFilterName($column);
    
                $this->filters[] = [
                    'key' => $column,
                    'value' => $name,
                    'url' => !empty($options['route']) ? route($options['route'][0], $options['route'][1]) : ''
                ];
            }
        }

        return view('components.search-string');
    }

    protected function getFilterName($column)
    {
        if (strpos($column, '_id') !== false) {
            $column = str_replace('_id', '', $column);
        }

        $plural = Str::plural($column, 2);

        if (trans_choice('general.' . $plural, 1) !== 'general.' . $plural) {
            return trans_choice('general.' . $plural, 1);
        } elseif (trans_choice('search_string.colmuns.' . $plural, 1) !== 'search_string.colmuns.' . $plural) {
            return trans_choice('search_string.colmuns.' . $plural, 1);
        }

        $name = trans('general.' . $column);
    
        if ($name == 'general.' . $column) {
            $name = trans('search_string.colmuns.' . $column);
        }

        return $name;
    }
}
