<div>
    <div
        @class([
            'd-flex align-items-center justify-content-between rp-border-bottom-1 text' => $is_print,
            'flex items-center justify-between text-xl text-black-400 border-b pb-2' => !$is_print
        ])
        >
            <h2>{{ $table_name }}</h2>
            <span>{{ $class->has_money ? money($grand_total) : $grand_total }}</span>
    </div>
    @if (!empty($class->row_values[$table_key]))
        <ul
            @class([
                'print-template text-normal' => $is_print,
                'space-y-2 my-3' => !$is_print
            ])
            >
            @foreach($class->row_tree_nodes[$table_key] as $id => $node)
                @include($class->views['summary.table.row'], ['tree_level' => 0])
            @endforeach
        </ul>
    @else
        <tr>
            <td colspan="{{ count($class->dates) + 2 }}">
                <div class="text-muted pl-0">{{ trans('general.no_records') }}</div>
            </td>
        </tr>
    @endif
</div>
