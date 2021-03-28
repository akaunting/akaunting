<thead class="thead-light">
    <tr class="row font-size-unset">
        @if (($table == 'default') && !empty($class->groups))
            <th class="{{ $class->column_name_width }}">{{ $class->groups[$class->model->settings->group] }}</th>
        @else
            <th class="{{ $class->column_name_width }}">{{ $table }}</th>
        @endif
        @foreach($class->dates as $date)
            <th class="{{ $class->column_value_width }} text-right px-0">{{ $date }}</th>
        @endforeach
        <th class="{{ $class->column_name_width }} text-right pl-0 pr-4">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
