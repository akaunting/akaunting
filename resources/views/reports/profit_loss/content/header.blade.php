<div class="table-responsive overflow-auto mt-4">
    <table class="table table-hover align-items-center rp-border-collapse">
        <thead class="border-top-style">
            <tr class="row rp-border-bottom-1 font-size-unset px-3">
                <th class="{{ $class->head_column_width }}"></th>
                @foreach($class->dates as $date)
                    <th class="{{ $class->column_width }}">{{ $date }}</th>
                @endforeach
                <th class="{{ $class->head_column_width }} text-uppercase">
                    {{ trans_choice('general.totals', 1) }}
                </th>
            </tr>
        </thead>
    </table>
</div>
