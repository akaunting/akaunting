@php $row_total = 0; @endphp
<tr>
    <td>{{ $class->getTableRowList()[$id] }}</td>
    @foreach($items as $item)
        @php $row_total += $item; @endphp
        <td class="text-right pl-0">@money($item, setting('default.currency'), true)</td>
    @endforeach
    <th class="text-right pl-0">@money($row_total, setting('default.currency'), true)</th>
</tr>
