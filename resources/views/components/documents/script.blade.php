@php
    $document_items = 'false';

    if ($items) {
        $document_items = json_encode($items);
    } else if (old('items')) {
        $document_items = json_encode(old('items'));
    }
@endphp

<script type="text/javascript">
    var document_items = {!! $document_items !!};
    var document_default_currency = '{{ $currency_code }}';
    var document_currencies = {!! $currencies !!};
    var document_taxes = {!! $taxes !!};
</script>

<script src="{{ asset( $scriptFile . '?v=' . $version) }}"></script>
