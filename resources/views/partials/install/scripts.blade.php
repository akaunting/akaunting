@stack('scripts_start')
<!-- Core -->
<script src="{{ asset('public/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('public/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

<script src="{{ asset('public/js/install.js?v=' . version('short')) }}"></script>

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

@livewireScripts

@stack('scripts_end')
