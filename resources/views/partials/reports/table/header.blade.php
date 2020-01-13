<thead class="thead-light">
    <tr>
        @if ($table == 'default')
            <th>{{ $class->groups[$class->report->group] }}</th>
        @else
            <th class="text-center">{{ $table }}</th>
        @endif
        @foreach($class->dates as $date)
            <th class="text-center">{{ $date }}</th>
        @endforeach
        <th class="text-center">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
