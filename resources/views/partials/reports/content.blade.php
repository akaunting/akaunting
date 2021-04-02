@include($class->views['content.header'])

@foreach($class->tables as $table)
    @include($class->views['table'])
@endforeach

@include($class->views['content.footer'])
