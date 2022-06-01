@stack('scripts_start')

<script src="{{ asset('public/js/install.min.js?v=' . version('short')) }}"></script>

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

@stack('scripts_end')
