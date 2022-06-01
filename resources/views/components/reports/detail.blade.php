@include($class->views['detail.content.header'])

@foreach($class->tables as $table_key => $table_name)
    @include($class->views['detail.table'])
@endforeach

@include($class->views['detail.content.footer'])
