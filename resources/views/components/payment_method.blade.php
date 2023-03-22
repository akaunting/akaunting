@if (! empty($payment_method))
    {!! $payment_method !!}
@else
    <x-empty-data />
@endif