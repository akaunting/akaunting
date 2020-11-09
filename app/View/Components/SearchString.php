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

        $this->filters = [];

        if (!empty($searc_string[$this->model])) {
            $columns = $searc_string[$this->model]['columns'];

            foreach ($columns as $column => $options) {
                // This column skip for filter
                if (!empty($options['searchable'])) {
                    continue;
                }
    
                if (!is_array($options)) {
                    $column = $options;
                }
    
                $this->filters[] = [
                    'key' => $this->getFilterKey($column, $options),
                    'value' => $this->getFilterName($column),
                    'type' => $this->getFilterType($options),
                    'url' => $this->getFilterUrl($column, $options),
                    'values' => $this->getFilterValues($column, $options),
                ];
            }
        }

        return view('components.search-string');
    }

    protected function getFilterKey($column, $options)
    {
        if (isset($options['relationship'])) {
            $column .= '.id';
        }

        return $column;
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

    protected function getFilterType($options) 
    {
        $type = 'select';

        if (isset($options['boolean'])) {
            $type = 'boolean';
        }

        return $type;
    }

    protected function getFilterUrl($column, $options) 
    {
        $url = '';

        if (isset($options['boolean'])) {
            return $url;
        }

        if (!empty($options['route'])) {
            if (is_array($options['route'])) {
                $url = route($options['route'][0], $options['route'][1]);
            } else {
                $url = route($options['route']);
            }
        } else {
            if (strpos($this->model, 'Modules') !== false) {
                $module_class = explode('\\', $this->model);

                $url .= Str::slug($module_class[1], '-') . '::';
            }
            
            if (strpos($column, '_id') !== false) {
                $column = str_replace('_id', '', $column);
            }
    
            $plural = Str::plural($column, 2);

            $url = route($url . $plural . '.index');
        }

        return $url;
    }

    protected function getFilterValues($column, $options) 
    {
        $values = [];

        if (isset($options['boolean'])) {
            $values = [
                [
                    'key' => 0,
                    'value' => trans('general.no'),
                ],
                [
                    'key' => 1,
                    'value' => trans('general.yes'),
                ],
            ];
        } else if ($search = request()->get('search', false)) {
            $fields = explode(' ', $search);

            foreach ($fields as $field) {
                if (strpos($field, ':') === false) {
                    continue;
                }

                $filters = explode(':', $field);

                if ($filters[0] != $column) {
                    continue;
                }
            }
        }

        return $values;
    }
}
