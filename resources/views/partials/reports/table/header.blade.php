<thead class="thead-light">
    <tr>
        @if ($table == 'default')
            <th>{{ $class->groups[$class->report->group] }}</th>
        @else
            <th class="text-right pl-0">{{ $table }}</th>
        @endif
        @foreach($class->dates as $date)
            <th class="text-right pl-0">{{ $date }}</th>
        @endforeach
        <th class="text-right pl-0">{{ trans_choice('general.totals', 1) }}</th>
    </tr>
</thead>
