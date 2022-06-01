@include($class->views['summary.content.header'])

@foreach($class->tables as $table_key => $table_name)
    <div class="flex flex-col lg:flex-row mt-12">
        @include($class->views['summary.table'])
        @include($class->views['summary.chart'])
    </div>
@endforeach

@include($class->views['summary.content.footer'])
