@push('scripts_start')
    <script type="text/javascript">
        var contact_default_currency = '{{ $currency_code }}';
        var contact_currencies = {!! $currencies !!};

        var can_login_errors = {
            valid: '{!! trans("validation.required", ["attribute" => "email"]) !!}',
            email: '{!! trans("customers.error.email") !!}'
        };
    </script>
@endpush

<x-script :alias="$alias" :folder="$folder" :file="$file" />
