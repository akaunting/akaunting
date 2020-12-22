@php
foreach ($class->footer_totals as $table => $dates) {
    foreach ($dates as $date => $total) {
        if (!isset($class->net_profit[$date])) {
            $class->net_profit[$date] = 0;
        }

        $class->net_profit[$date] += $total;
    }
}
@endphp

<div class="table-responsive my-2">
    <table class="table table-hover align-items-center rp-border-collapse">
        <tfoot class="border-top-style">
            <tr class="row rp-border-top-1 font-size-unset px-3">
                <th class="{{ $class->column_name_width }} text-uppercase text-left border-top-0">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="{{ $class->column_value_width }} text-right px-0 border-top-0">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="{{ $class->column_name_width }} text-right pl-0 pr-4 border-top-0">@money(array_sum($class->net_profit), setting('default.currency'), true)</th>
            </tr>
        </tfoot>
    </table>
</div>
