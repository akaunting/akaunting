<tfoot>
    <tr>
        <th>{{ trans('reports.net') }}</th>
        @php $total_total = 0; @endphp
        @foreach($class->totals[$table] as $total)
            @php $total_total += $total; @endphp
            <th class="text-right pl-0">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="text-right pl-0">@money($total_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
