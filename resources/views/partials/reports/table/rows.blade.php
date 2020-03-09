@php $row_total = 0; @endphp
<tr class="row rp-border-top-1 font-size-unset">
    <td class="{{ $class->head_column_width }}">{{ $class->row_names[$table][$id] }}</td>
    @foreach($rows as $row)
        @php $row_total += $row; @endphp
        <td class="{{ $class->column_width }}">@money($row, setting('default.currency'), true)</td>
    @endforeach
    <td class="{{ $class->head_column_width }}">@money($row_total, setting('default.currency'), true)</td>
</tr>
