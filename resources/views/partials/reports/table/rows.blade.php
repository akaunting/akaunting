@php $row_total = 0; @endphp
<tr class="rp-border-top-1">
    <td class="report-column" style="padding-left:{{ $class->indents['table_rows'] }}">{{ $class->row_names[$table][$id] }}</td>
    @foreach($rows as $row)
        @php $row_total += $row; @endphp
        <td class="report-column text-right px-0">@money($row, setting('default.currency'), true)</td>
    @endforeach
    <td class="report-column text-right">@money($row_total, setting('default.currency'), true)</td>
</tr>
