@php
    $normalized_amount = $amount;

    if (is_null($normalized_amount) || (is_string($normalized_amount) && trim($normalized_amount) === '')) {
        $normalized_amount = 0;
    }
@endphp

{{ money($normalized_amount, $currency, $convert) }}
