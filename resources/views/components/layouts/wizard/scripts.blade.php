@stack('scripts_start')
    <!-- Core -->
    <script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/akaunting-js/generalAction.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/akaunting-js/popper.js') }}"></script>

    <script type="text/javascript">
        var wizard_translations = {!! json_encode($translations) !!};
        var wizard_company = {!! json_encode($company) !!};
        var wizard_countries = {!! json_encode(trans('countries')) !!};
        var wizard_currencies = {!! json_encode($currencies) !!};
        var wizard_currency_codes = {!! json_encode($currency_codes) !!};
        var wizard_modules = {!! json_encode($modules) !!};
    </script>

    <script src="{{ asset('public/js/wizard/wizard.min.js?v=' . version('short')) }}"></script>

    @stack('body_css')

    @stack('body_stylesheet')

    @stack('body_js')

    @stack('body_scripts')

    @livewireScripts
@stack('scripts_end')
