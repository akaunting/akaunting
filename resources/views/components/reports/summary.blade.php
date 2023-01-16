@php
    $is_print = request()->routeIs('reports.print');
@endphp

@include($class->views['summary.content.header'])

@foreach($class->tables as $table_key => $table_name)
    <div class="flex flex-col lg:flex-row mt-12">
        @include($class->views['summary.table'])

        @if (! $is_print)
            @include($class->views['summary.chart'])
        @endif
    </div>
@endforeach

@include($class->views['summary.content.footer'])
