<div class="table-responsive overflow-auto mt-4">
    <table class="table table-hover align-items-center rp-border-collapse">
        <thead class="border-top-style">
            <tr>
                <th class="report-column text-right rp-border-bottom-1"></th>
                @foreach($class->dates as $date)
                    <th class="report-column text-right px-0 rp-border-bottom-1">{{ $date }}</th>
                @endforeach
                <th class="report-column text-right rp-border-bottom-1">{{ trans_choice('general.totals', 1) }}</th>
            </tr>
        </thead>
    </table>
</div>
