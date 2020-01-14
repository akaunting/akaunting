<div class="table-responsive overflow-auto mt-5">
    <table class="table align-items-center">
        <tfoot class="border-top-style">
            <tr>
                <th class="report-column">{{ trans('reports.net_profit') }}</th>
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
