<x-form id="report-show" action="{{ route('reports.show', $class->model->id) }}">
    @php
        $filters = [];
        $filtered = [];

        $skipped = [
            'keys', 'names', 'types', 'routes', 'multiple', 'defaults', 'operators',
        ];

        foreach ($class->filters as $filter_name => $filter_values) {
            if (in_array($filter_name, $skipped)) {
                continue;
            }

            $key = $filter_name;

            if (isset($class->filters['keys']) && !empty($class->filters['keys'][$filter_name])) {
                $key = $class->filters['keys'][$filter_name];
            } else if ($key == 'years') {
                $key = 'year';
            } else if ($key == 'customers' || $key == 'vendors') {
                $key = 'contact_id';
            } else {
                $key = Str::singular($key) . '_id';
            }

            $value = '';

            if (isset($class->filters['names']) && !empty($class->filters['names'][$filter_name])) {
                $value = $class->filters['names'][$filter_name];
            } else if (trans('reports.' . $filter_name) != 'reports.' . $filter_name) {
                $value = (strpos(trans('reports.' . $filter_name), '|') !== false) ? trans_choice('reports.' . $filter_name, 1) : trans('reports.' . $filter_name);
            } else {
                $value = (strpos(trans('general.' . $filter_name), '|') !== false) ? trans_choice('general.' . $filter_name, 1) : trans('general.' . $filter_name);
            }

            if ($key == 'year') {
                $value = trans('general.financial_year');
            }

            $type = 'select';

            if (isset($class->filters['types']) && !empty($class->filters['types'][$filter_name])) {
                $type = $class->filters['types'][$filter_name];
            }

            $url = '';

            if (isset($class->filters['routes']) && !empty($class->filters['routes'][$filter_name])) {
                $route = $class->filters['routes'][$filter_name];

                $url =  (is_array($route)) ? route($route[0], $route[1]) : route($route);
            }

            $default_value = null;

            if (isset($class->filters['defaults']) && !empty($class->filters['defaults'][$filter_name])) {
                $default_value = $class->filters['defaults'][$filter_name];
            }

            $operators = [];

            if (isset($class->filters['operators']) && !empty($class->filters['operators'][$filter_name])) {
                $operators = $class->filters['operators'][$filter_name];
            }

            if ($key == 'year') {
                //$default_value = \Date::now()->year;

                $operators = [
                    'equal'     => true,
                    'not_equal' => false,
                    'range'     => false,
                ];
            }

            $multiple = false;

            if (isset($class->filters['multiple']) && !empty($class->filters['multiple'][$filter_name])) {
                $multiple = $class->filters['multiple'][$filter_name];
            }

            $filters[] = [
                'key'       => $key,
                'value'     => $value,
                'type'      => $type,
                'url'       => $url,
                'values'    => $filter_values,
                'operators' => $operators,
                'multiple'  => $multiple ?? false, 
                'sort_options' => ($key == 'date_range') ? false : true,
            ];

            if (! is_null($default_value)) {
                $filtered[] = [
                    'option'    => $key,
                    'operator'  => '=',
                    'value'     => $default_value,
                    'operators' => $operators,
                ];
            }

            if (old($key) || request()->get($key)) {
                $filtered[] = [
                    'option'    => $key,
                    'operator'  => '=',
                    'value'     => old($key, request()->get($key)),
                    'operators' => $operators,
                ];
            }
        }
    @endphp

    <div class="items-center">
        <x-search-string :filters="$filters" :filtered="$filtered" />
    </div>
</x-form>
