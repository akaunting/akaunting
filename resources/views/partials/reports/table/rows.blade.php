@if ($row_total = array_sum($rows))
    <tr class="row rp-border-top-1 font-size-unset">
    <td class="{{ $class->column_name_width }} long-texts pr-0">{{ $class->row_names[$table][$id] }}</td>
        @foreach($rows as $row)
            <td class="{{ $class->column_value_width }} text-right px-0">@money($row, setting('default.currency'), true)</td>
        @endforeach
        <td class="{{ $class->column_name_width }} text-right pl-0 pr-4">@money($row_total, setting('default.currency'), true)</td>
    </tr>
@endif
