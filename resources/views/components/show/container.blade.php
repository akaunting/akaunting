@if (! empty($left) && $left->isNotEmpty())
    {!! $left !!}
@endif

@if (! empty($right) && $right->isNotEmpty())
    {!! $right !!}
@endif

{!! $slot !!}
