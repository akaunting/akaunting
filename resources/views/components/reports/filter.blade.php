<x-form id="report-show" action="{{ route('reports.show', $class->model->id) }}">
    @php
        $filters = [];
        $filtered = [];

        foreach ($class->getFilterChips() as $filter_name => $filter_values) {
            $key = $class->getFilterKey($filter_name);
            $value = $class->getFilterLabel($filter_name);

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

            $multiple = false;

            if (isset($class->filters['multiple']) && !empty($class->filters['multiple'][$filter_name])) {
                $multiple = $class->filters['multiple'][$filter_name];
            }

            $filters[] = [
                'key'       => $key,
                'value'     => $value,
                'operator'  => '=',
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
