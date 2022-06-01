@stack('scripts_start')
    @stack('body_css')

    @stack('body_stylesheet')

    @stack('body_js')

    @stack('body_scripts')

    @livewireScripts
    
    <script src="{{ asset('public/vendor/alpinejs/alpine.min.js') }}"></script>
@stack('scripts_end')
