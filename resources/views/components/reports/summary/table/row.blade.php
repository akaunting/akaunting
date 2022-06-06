<!-- if it HAS NOT subcategories -->
@if (is_null($node))
    @php
        $rows = $class->row_values[$table_key][$id];
    @endphp

    @if ($row_total = array_sum($rows))
        @if (isset($parent_id))
        <li class="collapse-sub" data-collapse="child-{{ $parent_id }}">
        @else
        <li>
        @endif
            <div class="flex justify-between border-0 m-0 p-0">
                @if (isset($parent_id))
                <div class="flex items-center" style="padding-left: {{ $tree_level * 20 }}px;">
                @else
                <div class="flex items-center">
                @endif
                    <span>{{ $class->row_names[$table_key][$id] }}</span>
                </div>
                <span>{{ $class->has_money ? money($row_total, default_currency(), true) : $row_total }}</span>
            </div>
        </li>
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
        <li class="collapse-sub" data-collapse="child-{{ $parent_id }}">
        @else
        <li>
        @endif
            <div class="flex justify-between border-0 m-0 p-0">
                <div class="flex items-center" style="padding-left: {{ $tree_level * 20 }}px;">
                    <span>{{ $class->row_names[$table_key][$id] }}</span>
                    @if (array_sum($parent_row_values) != array_sum($class->row_values[$table_key][$id]))
                    <button type="button" class="align-text-top flex" node="child-{{ $id }}" onClick="toggleSub('child-{{ $id }}', event)">
                        <span class="material-icons transform rotate-90 transition-all text-lg leading-none">navigate_next</span>
                    </button>
                    @endif
                </div>
                <span>{{ $class->has_money ? money($row_total, setting('default.currency'), true) : $row_total }}</span>
            </div>
        </li>
    @endif

    <!-- no categories part -->
    @php $rows = $class->row_values[$table_key][$id]; @endphp
    @if (($row_total = array_sum($rows)) && array_sum($parent_row_values) != array_sum($rows))
    <li class="collapse-sub" data-collapse="child-{{ $id }}">
        <div class="flex justify-between border-0 m-0 p-0">
            <div class="flex items-center" style="padding-left: {{ ($tree_level + 1) * 20 }}px;">
                <span>{{ $class->row_names[$table_key][$id] }}</span>
            </div>
            <span>{{ $class->has_money ? money($row_total, setting('default.currency'), true) : $row_total }}</span>
        </div>
    </li>
    @endif

    <!-- subcategories part -->
    @php
        $parent_id = $id;
        $tree_level++;
    @endphp

    @foreach($node as $id => $node)
        @if ($parent_id != $id)
            @include($class->views['summary.table.row'], ['parent_id' => $parent_id, 'tree_level' => $tree_level])
        @endif
    @endforeach
@endif
