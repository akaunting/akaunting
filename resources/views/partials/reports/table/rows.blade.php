@php $row_total = 0; @endphp
<tr class="rp-border-top-1">
    <td class="report-column">{{ $class->getTableRowList()[$id] }}</td>
    @foreach($items as $item)
        @php $row_total += $item; @endphp
        <td class="report-column text-right px-0">@money($item, setting('default.currency'), true)</td>
    @endforeach
    <td class="report-column text-right">@money($row_total, setting('default.currency'), true)</td>
</tr>
