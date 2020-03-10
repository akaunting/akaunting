<thead class="thead-light">
    <tr class="row font-size-unset">
        @if (($table == 'default') && !empty($class->groups))
            <th class="{{ $class->head_column_width }}">{{ $class->groups[$class->model->settings->group] }}</th>
        @else
            <th class="{{ $class->head_column_width }}">{{ $table }}</th>
        @endif
        @foreach($class->dates as $date)
            <th class="{{ $class->column_width }} text-right px-0">{{ $date }}</th>
        @endforeach
        <th class="{{ $class->head_column_width }} text-right">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
