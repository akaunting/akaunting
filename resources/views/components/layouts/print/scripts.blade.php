<!-- Core -->
<script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

<script type="text/javascript">
    var company_currency_code = '{{ default_currency() }}';
</script>

@stack('scripts_start')

@apexchartsScripts

@stack('charts')

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

<!-- should queue control added for bulk action download -->
@if (! should_queue())
    @livewireScripts
@endif

@stack('scripts_end')
