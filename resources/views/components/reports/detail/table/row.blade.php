<!-- if it HAS NOT subcategories -->
@if (is_null($node))
    @php
        $rows = $class->row_values[$table_key][$id];
    @endphp

    @if ($row_total = array_sum($rows))
        @if (isset($parent_id))
            <tr class="hover:bg-gray-100 border-b collapse-sub collapse-sub-report" data-collapse="child-{{ $parent_id }}">
                <td class="{{ $class->column_name_width }} py-2 text-left text-black-400" style="padding-left: {{ $tree_level * 20 }}px;" title="{{ $class->row_names[$table_key][$id] }}">{{ $class->row_names[$table_key][$id] }}</td>
        @else
            <tr class="hover:bg-gray-100 border-b">
                <td class="{{ $class->column_name_width }} py-2 text-left text-black-400" title="{{ $class->row_names[$table_key][$id] }}">{{ $class->row_names[$table_key][$id] }}</td>
        @endif

        @foreach($rows as $row)
            <td class="{{ $class->column_value_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs">{{ $class->has_money ? money($row) : $row }}</td>
        @endforeach
        <td class="{{ $class->column_name_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs uppercase">{{ $class->has_money ? money($row_total) : $row }}</td>
    </tr>
    @endif
@endif

<!-- if it HAS subcategories -->
@if (is_array($node))
    <!-- parent part -->
    @php
        $parent_row_values = $class->row_values[$table_key][$id];

        array_walk_recursive($node, function ($value, $key) use ($class, $table_key, $id, &$parent_row_values) {
            if ($key == $id) {
                return;
            }

            foreach($class->row_values[$table_key][$key] as $date => $amount) {
                $parent_row_values[$date] += $amount;
            }
        });
    @endphp

    @if ($row_total = array_sum($parent_row_values))
        @if (isset($parent_id))
            <tr class="hover:bg-gray-100 border-b collapse-sub collapse-sub-report" data-collapse="child-{{ $parent_id }}">
                <td class="{{ $class->column_name_width }} w-24 py-2 text-left text-black-400" style="padding-left: {{ $tree_level * 20 }}px;" title="{{ $class->row_names[$table_key][$id] }}">
        @else
            <tr class="hover:bg-gray-100 border-b">
                <td class="{{ $class->column_name_width }} w-24 py-2 text-left text-black-400" title="{{ $class->row_names[$table_key][$id] }}">
        @endif

        <div class="flex items-center">
            {{ $class->row_names[$table_key][$id] }}
            @if (array_sum($parent_row_values) != array_sum($class->row_values[$table_key][$id]))
                <button type="button" class="align-text-top flex" node="child-{{ $id }}" onClick="toggleSub('child-{{ $id }}', event)">
                    <span class="material-icons transform rotate-90 transition-all text-lg leading-none mt-.05">expand_less</span>
                </button>
            @endif
        </div>
        </td>
        @foreach($parent_row_values as $row)
            <td class="{{ $class->column_value_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs">{{ $class->has_money ? money($row) : $row }}</td>
        @endforeach
        <td class="{{ $class->column_name_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs uppercase">{{ $class->has_money ? money($row_total) : $row }}</td>
    </tr>
    @endif

    <!-- no categories part -->
    @php $rows = $class->row_values[$table_key][$id]; @endphp
    @if (($row_total = array_sum($rows)) && array_sum($parent_row_values) != array_sum($rows))
    <tr class="hover:bg-gray-100 border-b collapse-sub collapse-sub-report" data-collapse="child-{{ $id }}">
        <td class="{{ $class->column_name_width }} py-2 text-left text-black-400" style="padding-left: {{ ($tree_level + 1) * 20 }}px;" title="{{ $class->row_names[$table_key][$id] }}">{{ $class->row_names[$table_key][$id] }}</td>
        @foreach($rows as $row)
            <td class="{{ $class->column_value_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs">{{ $class->has_money ? money($row) : $row }}</td>
        @endforeach
        <td class="{{ $class->column_name_width }} py-2 ltr:text-right rtl:text-left text-alignment-right text-black-400 text-xs uppercase">{{ $class->has_money ? money($row_total) : $row }}</td>
    </tr>
    @endif

    <!-- subcategories part -->
    @php
        $parent_id = $id;
        $tree_level++;
    @endphp

    @foreach($node as $id => $node)
        @if ($parent_id != $id)
            @include($class->views['detail.table.row'], ['parent_id' => $parent_id, 'tree_level' => $tree_level])
        @endif
    @endforeach
@endif
