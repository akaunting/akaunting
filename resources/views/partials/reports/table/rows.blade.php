@php $row_total = 0; @endphp
<tr>
    <td class="report-column rp-border-top-1">{{ $class->getTableRowList()[$id] }}</td>
    @foreach($items as $item)
        @php $row_total += $item; @endphp
        <td class="report-column text-right px-0 rp-border-top-1">@money($item, setting('default.currency'), true)</td>
    @endforeach
    <td class="report-column text-right rp-border-top-1">@money($row_total, setting('default.currency'), true)</td>
</tr>
