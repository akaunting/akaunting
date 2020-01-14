<thead class="thead-light">
    <tr>
        @if ($table == 'default')
            <th>{{ $class->groups[$class->report->group] }}</th>
        @else
            <th>{{ $table }}</th>
        @endif
        @foreach($class->dates as $date)
            <th class="text-right px-0">{{ $date }}</th>
        @endforeach
        <th class="text-right">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
