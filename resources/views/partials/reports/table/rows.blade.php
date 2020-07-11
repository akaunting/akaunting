@if ($row_total = array_sum($rows))
    <tr class="row rp-border-top-1 font-size-unset">
        <td class="{{ $class->column_name_width }} long-texts pr-0">
            <el-tooltip
            content="{{ $class->row_names[$table][$id] }}"
            effect="dark"
            :open-delay="100"
            placement="top">
                <span>{{ $class->row_names[$table][$id] }}</span>
            </el-tooltip>
        </td>
        @foreach($rows as $row)
            <td class="{{ $class->column_value_width }} text-right px-0">@money($row, setting('default.currency'), true)</td>
        @endforeach
        <td class="{{ $class->column_name_width }} text-right pl-0 pr-4">@money($row_total, setting('default.currency'), true)</td>
    </tr>
@endif
