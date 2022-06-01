@php $grand_total = array_sum($class->footer_totals[$table_key]); @endphp

<div class="w-full lg:w-6/12">
    @include($class->views['summary.table.header'])
    @include($class->views['summary.table.body'])
    @include($class->views['summary.table.footer'])
</div>
