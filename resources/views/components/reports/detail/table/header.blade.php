<thead class="thead-light">
    <tr>
        @if (($table_key == 'default') && !empty($class->groups))
            <th class="{{ $class->column_name_width }}">{{ $class->groups[$class->getGroup()] ?? $table_name }}</th>
        @else
            <th class="{{ $class->column_name_width }}">{{ $table_name }}</th>
        @endif

        @foreach($class->dates as $date)
            <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left px-0">{{ $date }}</th>
        @endforeach
        <th class="{{ $class->column_name_width }} ltr:text-right rtl:text-left ltr:pl-0 rtl:pr-0 ltr:pr-4 rtl:pl-4">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
