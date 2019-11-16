<div class="table-responsive mt-4">
    <table class="table align-items-center">
        <thead class="border-top-style">
            <tr>
                <th>&nbsp;</th>
                @foreach($class->dates as $date)
                    <th class="text-right pl-0">{{ $date }}</th>
                @endforeach
                <th class="text-right pl-0">{{ trans_choice('general.totals', 1) }}</th>
            </tr>
        </thead>
    </table>
</div>
