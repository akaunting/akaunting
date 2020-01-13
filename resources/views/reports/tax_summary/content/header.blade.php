<div class="table-responsive overflow-auto">
    <table class="table align-items-center">
        <thead class="border-top-style">
            <tr>
                <th class="long-texts report-column text-center"></th>
                @foreach($class->dates as $date)
                    <th class="long-texts report-column text-center">{{ $date }}</th>
                @endforeach
                <th class="long-texts report-column text-right">
                    <h5>{{ trans_choice('general.totals', 1) }}</h5>
                </th>
            </tr>
        </thead>
    </table>
</div>
