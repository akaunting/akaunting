    <!-- Core -->
    <script src="{{ asset('public/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

    @stack('scripts_start')

    <script src="{{ asset('public/js/common/search.js?v=' . version('short')) }}"></script>

    @stack('charts')

    <script src="{{ asset('public/vendor/chart.js/dist/Chart.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js" charset=utf-8></script>

    <!-- Argon -->
    <script type="text/javascript">
        'use strict';

        var Layout = (function() {

            function pinSidenav() {
                $('.sidenav-toggler').addClass('active');
                $('.sidenav-toggler').data('action', 'sidenav-unpin');
                $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-show g-sidenav-pinned');
                $('body').append('<div class="backdrop d-xl-none" data-action="sidenav-unpin" data-target='+$('#sidenav-main').data('target')+' />');

                // Store the sidenav state in a cookie session
                Cookies.set('sidenav-state', 'pinned');
            }

            function unpinSidenav() {
                $('.sidenav-toggler').removeClass('active');
                $('.sidenav-toggler').data('action', 'sidenav-pin');
                $('body').removeClass('g-sidenav-pinned').addClass('g-sidenav-hidden');
                $('body').find('.backdrop').remove();

                // Store the sidenav state in a cookie session
                Cookies.set('sidenav-state', 'unpinned');
            }

            // Set sidenav state from cookie

            var $sidenavState = Cookies.get('sidenav-state') ? Cookies.get('sidenav-state') : 'pinned';

            if($(window).width() > 1200) {
                if($sidenavState == 'pinned') {
                    pinSidenav()
                }

                if(Cookies.get('sidenav-state') == 'unpinned') {
                    unpinSidenav()
                }
            }

            $("body").on("click", "[data-action]", function(e) {
                e.preventDefault();

                var $this = $(this);
                var action = $this.data('action');
                var target = $this.data('target');

                // Manage actions
                switch (action) {
                    case 'sidenav-pin':
                        pinSidenav();
                        break;

                    case 'sidenav-unpin':
                        unpinSidenav();
                        break;

                    case 'search-show':
                        target = $this.data('target');
                        $('body').removeClass('g-navbar-search-show').addClass('g-navbar-search-showing');

                        setTimeout(function() {
                            $('body').removeClass('g-navbar-search-showing').addClass('g-navbar-search-show');
                        }, 150);

                        setTimeout(function() {
                            $('body').addClass('g-navbar-search-shown');
                        }, 300)
                        break;

                    case 'search-close':
                        target = $this.data('target');
                        $('body').removeClass('g-navbar-search-shown');

                        setTimeout(function() {
                            $('body').removeClass('g-navbar-search-show').addClass('g-navbar-search-hiding');
                        }, 150);

                        setTimeout(function() {
                            $('body').removeClass('g-navbar-search-hiding').addClass('g-navbar-search-hidden');
                        }, 300);

                        setTimeout(function() {
                            $('body').removeClass('g-navbar-search-hidden');
                        }, 500);
                        break;
                }
            })

            // Add sidenav modifier classes on mouse events
            $('.sidenav').on('mouseenter', function() {
                if(! $('body').hasClass('g-sidenav-pinned')) {
                    $('body').removeClass('g-sidenav-hide').removeClass('g-sidenav-hidden').addClass('g-sidenav-show');
                }
            })

            $('.sidenav').on('mouseleave', function() {
                if(! $('body').hasClass('g-sidenav-pinned')) {
                    $('body').removeClass('g-sidenav-show').addClass('g-sidenav-hide');

                    setTimeout(function() {
                        $('body').removeClass('g-sidenav-hide').addClass('g-sidenav-hidden');
                    }, 300);
                }
            })

            // Make the body full screen size if it has not enough content inside
            $(window).on('load resize', function() {
                if($('body').height() < 800) {
                    $('body').css('min-height', '100vh');
                    $('#footer-main').addClass('footer-auto-bottom')
                }
            })
        })();
    </script>

    @stack('body_css')

    @stack('body_stylesheet')

    @stack('body_js')

    @stack('body_scripts')

@stack('scripts_end')
