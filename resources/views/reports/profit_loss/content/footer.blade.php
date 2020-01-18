<div class="table-responsive overflow-auto mt-5">
    <table class="table align-items-center">
        <tfoot class="border-top-style">
            <tr>
                <th class="report-column rp-border-top-1">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="report-column text-right px-0 rp-border-top-1">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="report-column text-right rp-border-top-1">
                    @money(array_sum($class->net_profit), setting('default.currency'), true)
                </th>
            </tr>
        </tfoot>
    </table>
</div>
