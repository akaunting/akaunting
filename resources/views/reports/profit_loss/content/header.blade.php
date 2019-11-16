<div class="table-responsive mt-4 overflow-auto">
    <table class="table align-items-center">
        <thead class="border-top-style">
            <tr>
                <th style="width: 152px;"></th>
                @foreach($class->dates as $date)
                    <th class="text-right">{{ $date }}</th>
                @endforeach
                <th class="text-right">{{ trans_choice('general.totals', 1) }}</th>
            </tr>
        </thead>
    </table>
</div>
