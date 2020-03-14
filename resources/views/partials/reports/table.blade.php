<div class="table-responsive">
    <table class="table table-hover align-items-center rp-border-collapse">
        @include($class->views['table.header'])
        <tbody>
            @if (!empty($class->row_values[$table]))
                @foreach($class->row_values[$table] as $id => $rows)
                    @include($class->views['table.rows'])
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($class->dates) + 2 }}">
                        <h5 class="text-center pl-0">{{ trans('general.no_records') }}</h5>
                    </td>
                </tr>
            @endif
        </tbody>
        @include($class->views['table.footer'])
    </table>
</div>
