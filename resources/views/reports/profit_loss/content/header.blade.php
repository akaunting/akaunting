<div class="table-responsive overflow-auto">
    <table class="table align-items-center rp-border-collapse">
        <thead class="border-top-style">
            <tr class="rp-border-bottom-1">
                <th class="report-column text-right"></th>
                @foreach($class->dates as $date)
                    <th class="report-column text-right px-0">{{ $date }}</th>
                @endforeach
                <th class="report-column text-right">
                    {{ trans_choice('general.totals', 1) }}
                </th>
            </tr>
        </thead>
    </table>
</div>
