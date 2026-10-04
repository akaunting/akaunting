{{-- The Excel export: the applied chips, then the report. A value spans the name and period columns' width, so a long one does not widen the first period column. --}}
@if (! empty($class->views['applied']))
    @include($class->views['applied'], ['colspan' => count($class->dates) ? count($class->dates) + 1 : 3])
@endif

@include($report_view)
