@php $row_total = 0; @endphp
<tr>
    <td class="long-texts report-column">{{ $class->getTableRowList()[$id] }}</td>
    @foreach($items as $item)
        @php $row_total += $item; @endphp
        <td class="long-texts report-column">@money($item, setting('default.currency'), true)</td>
    @endforeach
    <td class="long-texts report-column">@money($row_total, setting('default.currency'), true)</td>
</tr>
