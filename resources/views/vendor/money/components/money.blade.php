@php
dd($amount, $currency, $convert);
    $normalized_amount = $amount;

    if (is_null($normalized_amount) || (is_string($normalized_amount) && trim($normalized_amount) === '')) {
        $normalized_amount = 0;
    }

    // Defensive: cast non-numeric values (e.g. boolean false) to 0
    // so the money() helper never receives an unsupported type.
    if (!is_numeric($normalized_amount) && !($normalized_amount instanceof \Akaunting\Money\Money)) {
        $normalized_amount = 0;
    }
@endphp

{{ money($normalized_amount, $currency, $convert) }}
