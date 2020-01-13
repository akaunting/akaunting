<div class="table-responsive overflow-auto mt-5">
    <table class="table align-items-center">
        <tfoot class="border-top-style">
            <tr>
                <th class="long-texts report-column">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="long-texts report-column">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="long-texts report-column">
                    @money(array_sum($class->net_profit), setting('default.currency'), true)
                </th>
            </tr>
        </tfoot>
    </table>
</div>
