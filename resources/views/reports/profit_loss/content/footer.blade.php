<div class="table-responsive overflow-auto my-4">
    <table class="table table-hover align-items-center rp-border-collapse">
        <tfoot class="border-top-style">
            <tr class="rp-border-top-1">
                <th class="report-column text-uppercase text-left text-nowrap">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="report-column text-right px-0">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="report-column text-right">
                    @money(array_sum($class->net_profit), setting('default.currency'), true)
                </th>
            </tr>
        </tfoot>
    </table>
</div>
