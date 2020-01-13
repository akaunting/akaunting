<tfoot>
    <tr>
        <th class="long-texts report-column">{{ trans('reports.net') }}</th>
        @php $total_total = 0; @endphp
        @foreach($class->totals[$table] as $total)
            @php $total_total += $total; @endphp
            <th class="long-texts report-column">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="long-texts report-column">@money($total_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
