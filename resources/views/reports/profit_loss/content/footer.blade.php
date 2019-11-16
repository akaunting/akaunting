<div class="table-responsive mt-4 overflow-auto">
    <table class="table align-items-center">
        <thead class="border-top-style">
            <tr>
                <th style="width: 179px;">{{ trans('reports.net_profit') }}</th>
                @foreach($class->net_profit as $profit)
                    <th class="text-right">@money($profit, setting('default.currency'), true)</th>
                @endforeach
                <th class="text-right">@money(array_sum($class->net_profit), setting('default.currency'), true)</th>
            </tr>
        </thead>
    </table>
</div>
