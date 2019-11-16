<tfoot>
    <tr>
        <th style="width: 179px;">{{ trans_choice('general.totals', 1) }}</th>
            @php $total_total = 0; @endphp
            @foreach($class->totals[$table] as $date => $total)
                @php $total_total += $total; @endphp
                <th class="text-right">@money($total, setting('default.currency'), true)</th>
            @endforeach
        <th class="text-right">@money($total_total, setting('default.currency'), true)</th>
    </tr>
</tfoot>
