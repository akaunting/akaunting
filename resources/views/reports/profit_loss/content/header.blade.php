<div class="overflow-x-visible my-8">
    <table class="w-full rp-border-collapse">
        <thead>
            <tr>
                <th class="{{ $class->column_name_width }} w-24 print-alignment">&nbsp;</th>

                @foreach($class->dates as $date)
                <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase print-alignment">
                    {{ $date }}
                </th>
                @endforeach

                <th class="{{ $class->column_name_width }} ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase print-alignment">
                    {{ trans_choice('general.totals', 1) }}
                </th>
            </tr>
        </thead>
    </table>
</div>
