<div class="table-responsive overflow-auto my-4">
    <table class="table table-hover align-items-center rp-border-collapse">
        <tfoot class="border-top-style">
            <tr class="row rp-border-top-1 font-size-unset px-3">
                <th class="{{ $class->head_column_width }} text-uppercase text-nowrap">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="{{ $class->column_width }} text-right px-0">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="{{ $class->head_column_width }} text-right">
                    @money(array_sum($class->net_profit), setting('default.currency'), true)
                </th>
            </tr>
        </tfoot>
    </table>
</div>
