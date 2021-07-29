    <!-- Core -->
    <script src="{{ asset('public/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

    <script type="text/javascript">
        var company_currency_code = '{{ setting("default.currency") }}';
    </script>
    
    @stack('scripts_start')

    @stack('charts')

    <script src="{{ asset('public/vendor/chart.js/dist/Chart.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js" charset=utf-8></script>

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
