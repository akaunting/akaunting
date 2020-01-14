<tfoot>
    <tr>
        <th class="report-column">{{ trans('reports.net') }}</th>
        @php $total_total = 0; @endphp
        @foreach($class->totals[$table] as $total)
            @php $total_total += $total; @endphp
            <th class="report-column text-right px-0">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="report-column text-right">@money($total_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
