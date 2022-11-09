<!-- Core -->
<script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

<script type="text/javascript">
    var company_currency_code = '{{ default_currency() }}';
    var sentry_dsn = "https://9ffc5a5f104d4087911f60714e8bdbe9@o4503982427078656.ingest.sentry.io/4503999793594368";
</script>

@stack('scripts_start')

@apexchartsScripts

@stack('charts')

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

@livewireScripts

<!-- Livewire -->
<script type="text/javascript">
    window.livewire_app_url = {{ company_id() }};
</script>

@stack('scripts_end')
