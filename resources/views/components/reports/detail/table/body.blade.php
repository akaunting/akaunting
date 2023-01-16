<tbody>
    @if (!empty($class->row_values[$table_key]))
        @foreach($class->row_tree_nodes[$table_key] as $id => $node)
            @include($class->views['detail.table.row'], ['tree_level' => 0])
        @endforeach
    @else
        <tr>
            <td colspan="{{ count($class->dates) + 2 }}">
                <div class="text-muted pl-0">{{ trans('general.no_records') }}</div>
            </td>
        </tr>
    @endif
</tbody>
