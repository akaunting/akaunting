<tfoot>
    <tr class="rp-border-top-1">
        <th class="report-column text-left text-uppercase">{{ trans_choice('general.totals', 1) }}</th>
        @php $grand_total = 0; @endphp
        @foreach($class->footer_totals[$table] as $total)
            @php $grand_total += $total; @endphp
            <th class="report-column text-right px-0">@money($total, setting('default.currency'), true)</th>
        @endforeach
        <th class="report-column text-right">@money($grand_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
